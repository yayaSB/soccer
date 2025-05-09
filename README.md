# ⚽ Soccer Management System

This is a simple **Soccer Player Training Management System** developed in PHP and MySQL. It allows **admins**, **coaches**, and **players** to interact through a centralized web platform.

## 🧩 Features

### For All Users:
- Secure login & registration system.
- Role-based access (Player / Coach / Admin).

### Player:
- View personal training sessions.
- Access profile information and training history.

### Coach:
- Assign training sessions to players.
- View player information and schedules.

### Admin:
- Manage users (players & coaches).
- Monitor all training activities.

---

## 🛠️ Installation (Local)

### Prerequisites
- [XAMPP](https://www.apachefriends.org/)
- [Git](https://git-scm.com/)
- A web browser

### Steps

1. **Clone the project**
   ```bash
   git clone https://github.com/yayaSB/soccer.git
2.Move the project to XAMPP htdocs folder

3.Start Apache and MySQL via XAMPP Control Panel.

4.Import the database:

Open http://localhost/phpmyadmin

Create a new database (e.g., soccer_db)

Import the .sql file provided in the project (e.g., soccer_db.sql)

5.Configure the database connection:
Open config.php (or wherever the DB config is), and make sure it matches

 Access the app:
http://localhost/soccer/
