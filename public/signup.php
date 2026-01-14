<?php
require "../config/db.php";
require "../config/mailer.php";
require "../config/security.php";
session_start();

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Check password match
    if ($_POST['password'] !== $_POST['re_password']) {
        $error = "Passwords do not match.";
    } else {
        try {
            // 1. Hash password
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // 2. Insert new user
            $stmt = $pdo->prepare(
                "INSERT INTO users (first_name, last_name, email, password, two_factor_enabled) 
                 VALUES (?,?,?,?,?)"
            );
            // If checkbox checked, mark two_factor_enabled=1, else 0
            $twoFA = isset($_POST['enable_2fa']) ? 1 : 0;

            $stmt->execute([
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['email'],
                $password,
                $twoFA
            ]);

            $userId = $pdo->lastInsertId();

            // 3. Generate OTP (hashed, valid 24h)
            $otpPlain = rand(100000, 999999);
            $otpHash  = password_hash($otpPlain, PASSWORD_DEFAULT);
            $expires  = date("Y-m-d H:i:s", strtotime("+1 day"));

            $stmt = $pdo->prepare(
                "INSERT INTO otp_codes (user_id, otp, purpose, expires_at, last_sent_at)
                 VALUES (?, ?, 'signup', ?, NOW())"
            );
            $stmt->execute([$userId, $otpHash, $expires]);

            // 4. Send OTP email
            sendMail(
                $_POST['email'],
                "Your Signup OTP",
                "<h3>Your OTP is: $otpPlain</h3>
                 <p>This OTP is valid for 24 hours.</p>"
            );

            // 5. Redirect to OTP page for verification
            header("Location: otp.php?purpose=signup&uid=$userId");
            exit;

        } catch (PDOException $e) {
            $error = "Signup failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - Calculator</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Sign Up</h2>

    <?php if ($error) echo "<p class='error'>".htmlspecialchars($error)."</p>"; ?>

    <form method="POST">
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="re_password" placeholder="Re-enter Password" required>

        <!-- Checkbox for enabling 2FA immediately -->
        <label>
            <input type="checkbox" name="enable_2fa" value="1">
            Enable Two-Factor Authentication (2FA)
        </label>

        <button type="submit">Sign Up</button>
    </form>

    <p>Already have an account? <a href="index.php">Login</a></p>
</div>
</body>
</html>
