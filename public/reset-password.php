<?php
require "../config/db.php";

$token = $_GET['token'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $stmt = $pdo->prepare(
        "SELECT * FROM password_resets WHERE token=? AND expires_at > NOW()"
    );
    $stmt->execute([$token]);
    $reset = $stmt->fetch();

    if ($reset) {
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE users SET password=? WHERE id=?")
            ->execute([$hash, $reset['user_id']]);

        $pdo->prepare("DELETE FROM password_resets WHERE user_id=?")
            ->execute([$reset['user_id']]);

        echo "Password updated";
    }
}
