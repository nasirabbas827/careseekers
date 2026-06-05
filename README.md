# careseekers

A PHP‑based web application that connects care seekers with qualified care workers. The platform provides separate portals for administrators, care seekers, and workers, enabling job posting, management, messaging, and status tracking.

---

## Overview

`careseekers` is a lightweight, open‑source solution for managing home‑care services. It offers:

* A **admin dashboard** for managing categories, users, and job listings.  
* A **care seeker portal** to post jobs, view accepted jobs, and communicate with workers.  
* A **worker portal** to browse available jobs, chat with care seekers, and update job status.

The repository contains the full source code, database schema, and documentation needed to deploy the application on a typical LAMP stack.

---

## Features

| Area | Key Capabilities |
|------|------------------|
| **Admin** | Add / edit / delete care categories, manage users (care seekers & workers), view & approve jobs, monitor accepted jobs, logout. |
| **Care Seeker** | Register / login, post new care jobs, view job status, send and receive messages, accept worker proposals, logout. |
| **Worker** | Browse available jobs, apply to jobs, chat with care seekers, update job status, logout. |
| **Common** | Responsive navigation bars, session handling, basic input validation, and a simple MySQL database schema. |

---

## Tech Stack

| Component | Technology |
|-----------|------------|
| **Backend** | PHP 7.x+ |
| **Database** | MySQL / MariaDB |
| **Web Server** | Apache (or any server supporting PHP) |
| **Front‑end** | HTML5, CSS3, minimal JavaScript (Bootstrap optional) |
| **Version Control** | Git |

---

## Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/yourusername/careseekers.git
   cd careseekers
   ```

2. **Create the database**

   ```sql
   -- In MySQL client or phpMyAdmin
   SOURCE Database/careseeker.sql;
   ```

3. **Configure database connection**

   Edit `config.php` (and the duplicate config files in `admin/`, `careseeker/`, `worker/` if you prefer separate configs) and replace the placeholder values with your own credentials:

   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'careseeker');
   define('DB_USER', 'YOUR_DB_USER');
   define('DB_PASS', 'YOUR_DB_PASSWORD');
   ```

4. **Set up the web server**

   - Place the project folder inside your web root (e.g., `/var/www/html/careseekers`).
   - Ensure the server has permission to read/write the files.
   - Enable PHP execution and restart Apache/Nginx.

5. **Optional: Secure the admin area**

   - Move `admin/` outside the public web root and adjust `include` paths, or protect it with `.htaccess` authentication.

6. **Verify installation**

   Open a browser and navigate to `http://your-domain/careseekers/`. You should see the home page and be able to access the login pages for admins, care seekers, and workers.

---

## Usage

### Admin

1. Open `admin/admin_login.php` and log in with the admin credentials (create an admin user directly in the database if none exist).
2. Use the navigation bar to:
   - **Add Categories** – `admin/add_categories.php`
   - **Edit Categories** – `admin/edit_category.php`
   - **Manage Users** – `admin/manage_careseekers.php`, `admin/manage_workers.php`
   - **Manage Jobs** – `admin/manage_jobs.php`
   - **View Accepted Jobs** – `admin/view_accepted_jobs.php`

### Care Seeker

1. Register via `care