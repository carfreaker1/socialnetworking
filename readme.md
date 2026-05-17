# 📱 Basic Social Media App

A simple social media web application built with **PHP (OOP)**, **MySQL**, **AJAX**, **jQuery**, HTML, CSS, and JavaScript.

Users can:
- ✅ Register with profile image upload
- ✅ Login securely using hashed passwords
- ✅ Logout with AJAX
- ✅ Access protected dashboard after login
- ✅ Upload profile image with preview + validation
- ✅ Client-side and server-side form validation

---

## 🚀 Features

### Authentication
- User Signup
- User Login (AJAX)
- User Logout (AJAX)
- Password hashing using `password_hash()`
- Session-based authentication
- Protected routes/pages

### Form Validation
- Name validation (alphabets + spaces only)
- Email validation
- Password minimum 6 characters
- Confirm password matching
- Image upload validation:
  - Max size: **2MB**
  - Allowed types:
    - JPG
    - JPEG
    - PNG
    - GIF

### Profile Features
- Default profile image
- Live image preview before upload
- Reset to default image after form submit

---

# 🛠️ Tech Stack

| Technology | Used For |
|---|---|
| PHP OOP | Backend logic |
| MySQL | Database |
| AJAX | Async requests |
| jQuery | DOM + AJAX |
| HTML5 | Structure |
| CSS3 | Styling |
| JavaScript | Validation |

---

# 📂 Project Structure

```bash
socialmedia/
│
├── classes/
│   ├── Database.php
│   └── User.php
│
├── uploads/
│
├── ajax.php
├── index.php
├── login.php
├── dashboard.php
├── logout.php
│
└── README.md
```

---

# ⚙️ Installation Guide

## 1. Clone Repository

```bash
git clone YOUR_GITHUB_REPO_URL
```

Example:

```bash
git clone https://github.com/username/socialmedia.git
```

---

## 2. Move Project to XAMPP

Move project folder to:

```bash
htdocs/
```

Example:

```bash
C:\xampp\htdocs\socialmedia
```

---

## 3. Start XAMPP

Start:

- Apache
- MySQL

---

## 4. Create Database

Open:

```bash
http://localhost/phpmyadmin
```

Create database:

```sql
socialmedia
```

---

## 5. Import Database

Database SQL file is available in GitHub repo.

Import:

```bash
database/socialmedia.sql
```

or use phpMyAdmin import.

---

## 6. Configure Database

Open:

```php
classes/Database.php
```

Update credentials if needed:

```php
private $host = "localhost";
private $db_name = "socialmedia";
private $username = "root";
private $password = "";
```

---

## 7. Run Project

Open browser:

```bash
http://localhost/socialmedia
```

---

# 🔐 Default Workflow

## Signup
1. Register new user
2. Upload profile image
3. Validate inputs
4. Save user

---

## Login
1. Enter email/password
2. AJAX request sent
3. Session created
4. Redirect to dashboard

---

## Logout
1. Click logout
2. AJAX destroys session
3. Redirect to login page

---

# 🧠 Security Used

- Prepared statements
- PDO
- Password hashing
- Session protection
- File extension validation
- File size validation

---

# 📸 Screenshots

Add screenshots here:

```md
![Signup](screenshots/signup.png)
![Login](screenshots/login.png)
![Dashboard](screenshots/dashboard.png)
```

---

# 🔮 Future Improvements

- Post creation
- Like system
- Comments
- Friend requests
- Chat system
- Notifications

---

# 👨‍💻 Author

**Aman Sharma**

MCA Student | PHP & Laravel Developer

Skills:
- PHP
- Laravel
- MySQL
- AJAX
- JavaScript

---

# ⭐ Support

If you like this project:

```bash
Star ⭐ this repository
```