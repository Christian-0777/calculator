Below is a **clean, professional `README.md`** you can copy directly into your project. It is written to match your **calculator PHP project with authentication, email verification, OTP, and MySQL**.

---

## 📐 Calculator Web Application

A secure, PHP-based calculator web application featuring user authentication, email verification, two-factor authentication (OTP), and password recovery. Built for learning and demonstration purposes using modern best practices.

---

## 🚀 Features

* User Registration & Login
* Email Verification via PHPMailer
* Two-Factor Authentication (OTP)
* Secure Password Hashing
* Forgot / Reset Password Flow
* Session-based Authentication
* MySQL Database Integration
* Composer Dependency Management
* Clean MVC-like Folder Structure

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
├─ assets/            # CSS, JS, images
├─ config/            # Database, auth, mailer config
├─ public/            # Public entry points (login, signup, home)
├─ sql/               # Database schema
├─ vendor/            # Composer dependencies (ignored in git)
├─ .env               # Environment variables (ignored)
├─ .gitignore
├─ composer.json
└─ README.md
```

---

## ⚙️ Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/Christian-0777/calculator.git
cd calculator
```

---

### 2. Install Dependencies

```bash
composer install
```

---

### 3. Configure Environment Variables

Create a `.env` file in the root directory:

```
DB_HOST=localhost
DB_NAME=calculator_db
DB_USER=root
DB_PASS=

SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USER=your_email@gmail.com
SMTP_PASS=your_app_password
```

---

### 4. Database Setup

1. Open **phpMyAdmin**
2. Create database:

   ```
   calculator_db
   ```
3. Import:

   ```
   sql/database.sql
   ```

---

### 5. Run the Project

* Place the project inside:

  ```
  C:\xampp\htdocs\
  ```
* Start **Apache** and **MySQL**
* Open browser:

  ```
  http://localhost/calculator/public
  ```

---

## 🔐 Security Notes

* Passwords are hashed using `password_hash()`
* OTP tokens have expiration
* Email verification required before login
* `.env` and `/vendor` are excluded from Git

---

## 📌 Usage

* Register a new account
* Verify your email
* Login with credentials
* Complete OTP verification (if enabled)
* Use the calculator securely

---

## 📄 License

This project is for **educational purposes only**.

---

## 👤 Author

**Christian**
GitHub: [https://github.com/Christian-0777](https://github.com/Christian-0777)

---
