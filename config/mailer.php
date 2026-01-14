<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function sendMail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'nickelodeonknows57@gmail.com';
    $mail->Password = 'mzmv fmrc qkwa eaop';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('nickelodeonknows57@gmail.com', 'Calculator App');
    $mail->addAddress($to);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $body;

    $mail->send();
}
