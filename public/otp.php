<?php
require "../config/db.php";
require "../config/mailer.php";
require "../config/security.php";
session_start();

$purpose = $_GET['purpose'] ?? '';
$userId  = $_GET['uid'] ?? '';
$error   = '';

if (!$purpose || !$userId) {
    die("Invalid request.");
}

/* ===============================
   SKIP OTP FOR TRUSTED DEVICE
   (LOGIN ONLY)
================================ */
if ($purpose === 'login' && isset($_COOKIE['remember_token']) && !isset($_SESSION['just_logged_out'])) {
    $stmt = $pdo->prepare(
        "SELECT id FROM trusted_devices
         WHERE user_id=? AND device_token=? AND expires_at > NOW()"
    );
    $stmt->execute([$userId, $_COOKIE['remember_token']]);

    if ($stmt->fetch()) {
        $_SESSION['user_id'] = $userId;
        header("Location: home.php");
        exit;
    }
}

/* ===============================
   RESEND OTP
================================ */
if (isset($_POST['resend'])) {

    if (!canSendOtp($pdo, $userId, $purpose)) {
        $error = "Please wait before requesting another OTP.";
    } else {

        $stmtUser = $pdo->prepare("SELECT email FROM users WHERE id=?");
        $stmtUser->execute([$userId]);
        $user = $stmtUser->fetch();

        if ($user) {
            $otpPlain = random_int(100000, 999999);
            $otpHash  = password_hash($otpPlain, PASSWORD_DEFAULT);

            $pdo->prepare("DELETE FROM otp_codes WHERE user_id=? AND purpose=?")
                ->execute([$userId, $purpose]);

            $pdo->prepare(
                "INSERT INTO otp_codes
                 (user_id, otp, purpose, expires_at, last_sent_at)
                 VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 1 DAY), NOW())"
            )->execute([$userId, $otpHash, $purpose]);

            sendMail(
                $user['email'],
                "Your OTP Code",
                "<h2>$otpPlain</h2><p>This OTP is valid for 24 hours.</p>"
            );

            $error = "OTP resent successfully.";
        }
    }
}

/* ===============================
   OTP VERIFICATION
================================ */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['otp'])) {

    $stmt = $pdo->prepare(
        "SELECT * FROM otp_codes
         WHERE user_id=? AND purpose=? AND expires_at > NOW()
         ORDER BY id DESC LIMIT 1"
    );
    $stmt->execute([$userId, $purpose]);
    $otpRow = $stmt->fetch();

    if ($otpRow && password_verify(trim($_POST['otp']), $otpRow['otp'])) {

        // Remove OTP after success
        $pdo->prepare("DELETE FROM otp_codes WHERE user_id=? AND purpose=?")
            ->execute([$userId, $purpose]);

        if ($purpose === 'signup') {
            $pdo->prepare("UPDATE users SET email_verified=1 WHERE id=?")
                ->execute([$userId]);
            $_SESSION['user_id'] = $userId;
            header("Location: home.php");
            exit;
        }

        if ($purpose === 'login') {
            $_SESSION['user_id'] = $userId;

            // TRUST THIS DEVICE
            if (isset($_POST['remember'])) {
                $token  = bin2hex(random_bytes(32));
                $expiry = date("Y-m-d H:i:s", strtotime("+30 days"));

                // Optional: remove old tokens for same device
                $pdo->prepare(
                    "DELETE FROM trusted_devices WHERE user_id=? AND user_agent=?"
                )->execute([$userId, $_SERVER['HTTP_USER_AGENT']]);

                $pdo->prepare(
                    "INSERT INTO trusted_devices
                     (user_id, device_token, device_name, user_agent, expires_at)
                     VALUES (?, ?, ?, ?, ?)"
                )->execute([$userId, $token, getDeviceName(), $_SERVER['HTTP_USER_AGENT'], $expiry]);

                setcookie(
                    "remember_token",
                    $token,
                    time() + (86400 * 30),
                    "/",
                    "",
                    false,
                    true
                );
            }

            header("Location: home.php");
            exit;
        }

    } else {
        $error = "Invalid or expired OTP.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>OTP Verification</h2>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="number" name="otp" placeholder="Enter OTP" required>
        <?php if ($purpose === 'login'): ?>
            <label>
                <input type="checkbox" name="remember"> Trust this device
            </label>
        <?php endif; ?>
        <button type="submit">Verify OTP</button>
    </form>

    <form method="POST" style="margin-top:10px;">
        <button type="submit" name="resend">Resend OTP</button>
    </form>

    <p><a href="index.php">Back to Login</a></p>
</div>
</body>
</html>
