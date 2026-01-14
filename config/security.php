<?php
// Enable error reporting for development (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Check if the user can request a new OTP (cooldown)
 * @param PDO $pdo
 * @param int $userId
 * @param string $purpose
 * @param int $cooldownSeconds
 * @return bool
 */
function canSendOtp($pdo, $userId, $purpose, $cooldownSeconds = 60) {
    $stmt = $pdo->prepare(
        "SELECT last_sent_at FROM otp_codes
         WHERE user_id=? AND purpose=?
         ORDER BY id DESC LIMIT 1"
    );
    $stmt->execute([$userId, $purpose]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) return true; // No previous OTP → can send

    $lastSent = strtotime($row['last_sent_at']);
    return (time() - $lastSent) > $cooldownSeconds;
}

/**
 * Detect device/browser from user agent string
 * @return string
 */
/**
 * Simple device and browser detection
 */
function getDeviceName() {
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

    // Device type
    if (stripos($ua, 'mobile') !== false) {
        $device = "Mobile";
    } elseif (stripos($ua, 'tablet') !== false) {
        $device = "Tablet";
    } else {
        $device = "Desktop";
    }

    // Browser detection
    if (stripos($ua, 'brave') !== false) {
        $browser = "Brave";
    } elseif (stripos($ua, 'chrome') !== false) {
        $browser = "Chrome";
    } elseif (stripos($ua, 'firefox') !== false) {
        $browser = "Firefox";
    } elseif (stripos($ua, 'safari') !== false && stripos($ua, 'chrome') === false) {
        $browser = "Safari";
    } elseif (stripos($ua, 'edge') !== false) {
        $browser = "Edge";
    } elseif (stripos($ua, 'opera') !== false || stripos($ua, 'opr') !== false) {
        $browser = "Opera";
    } else {
        $browser = "Unknown Browser";
    }

    return "$device - $browser";
}


/**
 * Generate a secure random token
 * @param int $length Bytes length (default 32 → 64 hex chars)
 * @return string
 */
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

/**
 * Validate OTP input
 * @param string $inputOtp
 * @param string $hashedOtp
 * @return bool
 */
function verifyOtp($inputOtp, $hashedOtp) {
    return password_verify(trim($inputOtp), $hashedOtp);
}
