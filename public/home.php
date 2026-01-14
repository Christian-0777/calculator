<?php
require "../config/auth.php";
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scientific Calculator</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">
    <h2>Scientific Calculator</h2>

    <input type="text" id="display" readonly>

    <div class="calc-grid">
        <!-- Row 1 -->
        <button onclick="clearDisplay()">C</button>
        <button onclick="backspace()">⌫</button>
        <button onclick="append('(')">(</button>
        <button onclick="append(')')">)</button>

        <!-- Row 2 -->
        <button onclick="append('7')">7</button>
        <button onclick="append('8')">8</button>
        <button onclick="append('9')">9</button>
        <button onclick="append('/')">÷</button>

        <!-- Row 3 -->
        <button onclick="append('4')">4</button>
        <button onclick="append('5')">5</button>
        <button onclick="append('6')">6</button>
        <button onclick="append('*')">×</button>

        <!-- Row 4 -->
        <button onclick="append('1')">1</button>
        <button onclick="append('2')">2</button>
        <button onclick="append('3')">3</button>
        <button onclick="append('-')">−</button>

        <!-- Row 5 -->
        <button onclick="append('0')">0</button>
        <button onclick="append('.')">.</button>
        <button onclick="calculate()">=</button>
        <button onclick="append('+')">+</button>

        <!-- Scientific -->
        <button onclick="func('sin')">sin</button>
        <button onclick="func('cos')">cos</button>
        <button onclick="func('tan')">tan</button>
        <button onclick="func('sqrt')">√</button>

        <button onclick="func('log')">log</button>
        <button onclick="func('ln')">ln</button>
        <button onclick="power()">x²</button>
        <button onclick="pi()">π</button>
    </div>

    <p style="margin-top:15px;">
        <a href="logout.php">Logout</a>
    </p>
</div>

<script src="../assets/js/calculator.js"></script>
</body>
</html>
