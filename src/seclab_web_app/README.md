# SecLab Web App - Setup Guide

This directory contains the source code for the vulnerable e-commerce application ("SecLab") used as a target in our SOC-in-a-Box environment.

> ⚠️ **WARNING:** This application is INTENTIONALLY VULNERABLE (SQL Injections, XSS, etc.). **Never deploy it on a production server or expose it to the Internet.** Use it only in an isolated local environment (Virtual Machine).

## 📋 Prerequisites
* A Web server (Apache2 or Nginx)
* PHP (with PDO and pgsql extensions: `php-pgsql`)
* PostgreSQL

## 🚀 Step 1: Deploy Files
Move the contents of this folder to your web server's root directory (often `/var/www/html` on Linux).

```bash
sudo cp -r src/seclab_web_app/* /var/www/html/
sudo chown -R www-data:www-data /var/www/html/
```

## 🗄️ Step 2: Database Configuration (PostgreSQL)

The application expects to communicate with a PostgreSQL database by default.

1. Connect to your PostgreSQL server:
   ```bash
   sudo -u postgres psql
   ```

2. Create the database and set the default password expected by the application:
   ```sql
   CREATE DATABASE "Test_Lab";
   ALTER USER postgres WITH PASSWORD '123456';
   \q
   ```

*(Note: If you have an SQL dump file `.sql` for the `users`, `products` tables, etc., don't forget to import it into your `Test_Lab` database).*
```bash
# Example import if you have a dump.sql file
sudo -u postgres psql -d Test_Lab -f your_file.sql
```

## ⚙️ Step 3: Configuration File (`config.php`)

If you want to use different credentials, open the `config.php` file located at the root of the project and modify these variables:

```php
$servername = "127.0.0.1";
$username   = "postgres";    // Database username
$password   = "123456";      // Database password
$dbname     = "Test_Lab";    // Database name
```

*(The application uses a "postgres_mysqli" proxy class in `config.php` to translate standard mysqli syntax to PDO PostgreSQL, without fixing the direct query vulnerabilities).*

## 🌐 Start the Application
Once the database is configured, restart your Web server and PostgreSQL:
```bash
sudo systemctl restart apache2
sudo systemctl restart postgresql
```

Then access the application via your browser: `http://localhost/` or your Target VM's IP address.
