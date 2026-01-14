<?php
require "../config/db.php";
require "../config/mailer.php";
require "../config/security.php";
session_start();

$error = '';

/* ===============================
   AUTO LOGIN (TRUSTED DEVICE)
   Skip 2FA if device is trusted
================================ */
if (!isset($_SESSION['just_logged_out']) && isset($_COOKIE['remember_token'])) {
    $stmt = $pdo->prepare(
        "SELECT user_id FROM trusted_devices
         WHERE device_token=? AND expires_at > NOW()"
    );
    $stmt->execute([$_COOKIE['remember_token']]);
    $device = $stmt->fetch();

    if ($device) {
        $_SESSION['user_id'] = $device['user_id'];
        header("Location: home.php");
        exit;
    }
}

/* ===============================
   LOGIN FORM SUBMISSION
================================ */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$_POST['email']]);
    $user = $stmt->fetch();

    if ($user) {

        /* ACCOUNT LOCK CHECK */
        if ($user['lock_until'] && strtotime($user['lock_until']) > time()) {
            $error = "Account locked. Try again later.";
        }

        /* PASSWORD CHECK */
        else if (password_verify($_POST['password'], $user['password'])) {

            // Reset failed attempts
            $pdo->prepare(
                "UPDATE users SET failed_attempts=0, lock_until=NULL WHERE id=?"
            )->execute([$user['id']]);

            /* EMAIL VERIFIED */
            if (!$user['email_verified']) {
                $error = "Email not verified.";
            }

            /* TWO-FACTOR AUTH */
            else if ($user['two_factor_enabled']) {

                $trusted = false;

                // Check if device is already trusted
                if (isset($_COOKIE['remember_token'])) {
                    $stmt = $pdo->prepare(
                        "SELECT id FROM trusted_devices
                         WHERE user_id=? AND device_token=? AND expires_at > NOW()"
                    );
                    $stmt->execute([$user['id'], $_COOKIE['remember_token']]);
                    if ($stmt->fetch()) {
                        $trusted = true;
                    }
                }

                if ($trusted) {
                    // Trusted device → skip OTP
                    $_SESSION['user_id'] = $user['id'];
                    header("Location: home.php");
                    exit;
                }

                // Device not trusted → send OTP
                if (!canSendOtp($pdo, $user['id'], 'login')) {
                    $error = "OTP already sent. Please wait.";
                } else {
                    // Delete old OTPs
                    $pdo->prepare(
                        "DELETE FROM otp_codes WHERE user_id=? AND purpose='login'"
                    )->execute([$user['id']]);

                    $otpPlain = random_int(100000, 999999);
                    $otpHash  = password_hash($otpPlain, PASSWORD_DEFAULT);

                    $pdo->prepare(
                        "INSERT INTO otp_codes
                         (user_id, otp, purpose, expires_at, last_sent_at)
                         VALUES (?, ?, 'login', DATE_ADD(NOW(), INTERVAL 1 DAY), NOW())"
                    )->execute([$user['id'], $otpHash]);

                    sendMail(
                        $user['email'],
                        "Login OTP",
                        "<h2>$otpPlain</h2><p>This OTP is valid for 24 hours.</p>"
                    );

                    header("Location: otp.php?purpose=login&uid=".$user['id']);
                    exit;
                }
            }

            /* LOGIN WITHOUT 2FA */
            else {
                $_SESSION['user_id'] = $user['id'];
                header("Location: home.php");
                exit;
            }

        } else {
            // PASSWORD INVALID
            $pdo->prepare(
                "UPDATE users
                 SET failed_attempts = failed_attempts + 1,
                     lock_until = IF(failed_attempts >= 4,
                         DATE_ADD(NOW(), INTERVAL 15 MINUTE),
                         lock_until)
                 WHERE id=?"
            )->execute([$user['id']]);

            $error = "Invalid email or password.";
        }

    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Calculator</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Login</h2>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>
    </form>

    <p>
        Don't have an account? <a href="signup.php">Sign Up</a><br>
        Forgot password? <a href="forgot-password.php">Reset Here</a>
    </p>
</div>
</body>
</html>
