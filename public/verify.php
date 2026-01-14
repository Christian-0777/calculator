<?php
require "../config/db.php";
session_start();

// Get token from URL
$token = $_GET['token'] ?? null;

if (!$token) {
    die("Invalid verification link.");
}

// Lookup token
$stmt = $pdo->prepare("SELECT * FROM email_verifications WHERE token=? AND expires_at > NOW()");
$stmt->execute([$token]);
$verify = $stmt->fetch();

if (!$verify) {
    die("Invalid or expired verification link.");
}

// Mark user as verified
$pdo->prepare("UPDATE users SET email_verified=1 WHERE id=?")->execute([$verify['user_id']]);

// Remove token after verification
$pdo->prepare("DELETE FROM email_verifications WHERE id=?")->execute([$verify['id']]);

// Log in the user automatically
$_SESSION['user_id'] = $verify['user_id'];

// Redirect to home
header("Location: home.php");
exit;
