<?php
require "../config/db.php";
require "../config/mailer.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);

    // Find user by email
    $stmt = $pdo->prepare("SELECT id, first_name FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {

        // Invalidate old reset tokens
        $pdo->prepare(
            "DELETE FROM password_resets WHERE user_id=?"
        )->execute([$user['id']]);

        // Generate secure reset token (valid 24 hours)
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime("+1 day")); // 24 hours

        $pdo->prepare(
            "INSERT INTO password_resets (user_id, token, expires_at)
             VALUES (?, ?, ?)"
        )->execute([$user['id'], $token, $expires]);

        $link = "http://localhost/calculator/public/reset-password.php?token=$token";

        // Send reset email
        sendMail(
            $email,
            "Reset Your Password",
            "<p>Hi {$user['first_name']},</p>
             <p>You requested a password reset.</p>
             <p>Click the link below to reset your password:</p>
             <p><a href='$link'>$link</a></p>
             <p>This link will expire in 24 hours.</p>
             <p>If you did not request this, please ignore this email.</p>"
        );
    }

    // Same message whether email exists or not (prevents enumeration)
    $message = "If the email exists, a password reset link has been sent.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Calculator</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Forgot Password</h2>

    <?php if (!empty($message)) echo "<p>$message</p>"; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit">Send Reset Link</button>
    </form>

    <p><a href="index.php">Back to Login</a></p>
</div>
</body>
</html>
