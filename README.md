GUIDE TO SET UP A SECURED CALCULATOR

---

# 📐 Calculator Web Application

A secure PHP-based calculator web application featuring authentication, email verification, two-factor authentication (OTP), and password recovery. Built using PHP, MySQL, PHPMailer, and Composer for educational and demonstration purposes.

---

## 🚀 Features

* User Registration & Login
* Email Verification (PHPMailer)
* Two-Factor Authentication (OTP)
* Forgot / Reset Password
* Secure Password Hashing
* Session-Based Authentication
* MySQL Database Integration
* Composer Dependency Management

---

## 🛠 Tech Stack

* **Frontend:** HTML, CSS, JavaScript
* **Backend:** PHP 8.x
* **Database:** MySQL (MySQL Workbench CE)
* **Email:** PHPMailer (SMTP)
* **Server:** Apache (XAMPP)
* **Dependency Manager:** Composer

---

## 📁 Project Structure

```
calculator/
│
├─ assets/            # CSS, JS, images
├─ config/            # Database, auth, mailer config
├─ public/            # Login, signup, home
├─ sql/               # Database schema
├─ vendor/            # Composer dependencies (ignored)
├─ .gitignore
├─ composer.json
└─ README.md
```

---

## ⚙️ Installation & Setup

---

## 1️⃣ Install Required Software

### Install XAMPP

1. Download XAMPP from:
   [https://www.apachefriends.org](https://www.apachefriends.org)
2. Install and open **XAMPP Control Panel**
3. Start:

   * **Apache**
   * **MySQL**

---

### Install MySQL Workbench CE

1. Download MySQL Workbench CE from:
   [https://dev.mysql.com/downloads/workbench/](https://dev.mysql.com/downloads/workbench/)
2. Open MySQL Workbench
3. Connect to:

   * Host: `localhost`
   * Port: `3306`
   * User: `root`

---

## 2️⃣ Clone the Repository

```bash
git clone https://github.com/Christian-0777/calculator.git
cd calculator
```

Move the project into XAMPP:

```
C:\xampp\htdocs\calculator
```

---

## 3️⃣ Install PHP Dependencies

Make sure **Composer** is installed.

```bash
composer install
```

This will generate the `/vendor` folder.

---

## 4️⃣ Database Setup (MySQL Workbench CE)

### Create Database

1. Open **MySQL Workbench**
2. Click **Create a new SQL tab**
3. Run:

```sql
CREATE DATABASE calculator_db;
USE calculator_db;
```

---

### Import Tables

Option A: Using SQL file

1. Open `sql/database.sql`
2. Copy contents
3. Paste into MySQL Workbench
4. Execute (⚡)

Option B: Using phpMyAdmin

* Import `sql/database.sql`

---

## 5️⃣ Configure Database Connection

Edit:

```
config/db.php
```

Example:

```php
<?php
$host = "localhost";
$db   = "calculator_db";
$user = "root";
$pass = "";

$conn = new PDO(
    "mysql:host=$host;dbname=$db;charset=utf8mb4",
    $user,
    $pass,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
);
```

---

## 6️⃣ Configure PHPMailer (`mailer.php`)

Edit:

```
config/mailer.php
```

### Gmail SMTP Example

```php
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/../vendor/autoload.php";

function sendMail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = "smtp.gmail.com";
        $mail->SMTPAuth   = true;
        $mail->Username   = "your_email@gmail.com";
        $mail->Password   = "your_app_password";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom("your_email@gmail.com", "Calculator App");
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
```

---

### Gmail App Password Setup

1. Enable **2-Step Verification** on Google Account
2. Go to **Google → App Passwords**
3. Generate a password for **Mail**
4. Use that password in `mailer.php`

⚠ **Do NOT use your Gmail account password**

---

## 7️⃣ Run the Application

1. Start **Apache** and **MySQL** in XAMPP
2. Open browser:

```
http://localhost/calculator/public
```

---

## 🔐 Security Notes

* Passwords hashed using `password_hash()`
* OTP codes are time-limited
* Email verification required
* `.env` and `/vendor` are excluded from GitHub

---

## 📌 Usage Flow

1. Register an account
2. Verify email via link
3. Login with credentials
4. Complete OTP (if enabled)
5. Access calculator dashboard

---

## 👤 Author

**Christian**
GitHub: [https://github.com/Christian-0777](https://github.com/Christian-0777)

---

## 🚧 Future Improvements

* Role-based access control
* Login attempt throttling
* Activity logging
* UI responsiveness
* Automated tests

---
