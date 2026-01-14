Got it! Here’s a **complete, updated `README.md`** for your project. It now includes **XAMPP/MySQL Workbench setup**, **mailer.php configuration**, **cloning instructions**, and installing Composer + PHPMailer from your GitHub repo. I’ve formatted it so someone can follow it step by step.

---

# 📐 Calculator Web Application

A secure PHP-based calculator web application with user authentication, email verification, OTP, and password recovery. Designed for learning and demonstration purposes.

---

## 🚀 Features

* User Registration & Login
* Email Verification via PHPMailer
* Two-Factor Authentication (OTP)
* Secure Password Hashing
* Forgot / Reset Password Flow
* MySQL Database Integration
* Composer Dependency Management

---

## 🛠 Tech Stack

* **Frontend:** HTML, CSS, JavaScript
* **Backend:** PHP (8.x)
* **Database:** MySQL / MariaDB
* **Email:** PHPMailer (SMTP)
* **Dependency Manager:** Composer
* **Server:** Apache (XAMPP)

---

## 📁 Project Structure

```
calculator/
│
├─ assets/          # CSS, JS, images
├─ config/          # Database & mailer config
├─ public/          # Public pages (login, signup, home)
├─ sql/             # Database schema
├─ vendor/          # Composer dependencies
├─ .gitignore
├─ composer.json
└─ README.md
```

---

## ⚙️ Installation & Setup

### 1. Install XAMPP

1. Download XAMPP from [https://www.apachefriends.org/download.html](https://www.apachefriends.org/download.html)
2. Install it to your preferred location (e.g., `C:\xampp`)
3. Start **Apache** and **MySQL** from XAMPP Control Panel

---

### 2. Install MySQL Workbench CE

1. Download MySQL Workbench CE from [https://dev.mysql.com/downloads/workbench/](https://dev.mysql.com/downloads/workbench/)
2. Install it
3. Create a new connection to your local MySQL server (usually `root` user, no password for local dev)

---

### 3. Create the Database

1. Open **MySQL Workbench**
2. Run the following SQL:

```sql
CREATE DATABASE calculator_db;
USE calculator_db;

-- Import the tables from sql/database.sql
```

3. Or you can use **phpMyAdmin**:

* Open `http://localhost/phpmyadmin`
* Create `calculator_db`
* Import `sql/database.sql`

---

### 4. Clone Project to XAMPP

After installing XAMPP:

```bash
cd C:\xampp\htdocs
git clone https://github.com/Christian-0777/calculator.git
```

Your project will be accessible at:

```
http://localhost/calculator/public
```

---

### 5. Install Composer and PHPMailer

Clone this repo to the project root:

```bash
cd C:\xampp\htdocs\calculator
git clone https://github.com/Christian-0777/composerandphpmailer.git
```
### 6. Configure `mailer.php`

Edit `config/mailer.php` (or wherever you put it) with your credentials:

```php
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function sendMail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@gmail.com';   // change this
    $mail->Password = 'your_app_password';      // change this
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('your_email@gmail.com', 'Calculator App'); // change this
    $mail->addAddress($to);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $body;

    $mail->send();
}
```
## 7. To get the your_email@gmail.com and your_app_password
* Gmail App Password Setup
* Enable 2-Step Verification on Google Account
* Go to Google → App Passwords
* Generate a password for Mail
* Use that password in mailer.php
## ⚠ Do NOT use your Gmail account password

---

## 🔐 Security Notes

* Passwords are hashed using `password_hash()`
* OTP tokens have expiration
* Email verification required before login
* `.env` and `/vendor` are excluded from Git

---

## 📌 Usage

1. Register a new account
2. Verify your email
3. Login with credentials
4. Complete OTP verification (if enabled)
5. Use the calculator securely

---

## 📄 License

This project is for **educational purposes only**.

---

## 👤 Author

**Christian**
GitHub: [https://github.com/Christian-0777](https://github.com/Christian-0777)

---
