<?php
require "../config/db.php";
session_start();

/* ===============================
   DESTROY SESSION
================================ */
$_SESSION = [];
session_destroy();

/* ===============================
   PREVENT AUTO-LOGIN LOOP
================================ */
session_start();
$_SESSION['just_logged_out'] = true;

/* ===============================
   REDIRECT TO LOGIN
================================ */
header("Location: index.php");
exit;
