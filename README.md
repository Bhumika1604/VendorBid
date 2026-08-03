# 🚀 VendorBid – Smart Contractor Bidding & Tender Management System

## 📌 Overview

**VendorBid** is a web-based **Contractor Bidding and Tender Management System** developed to simplify and automate the tender management process between organizations and contractors.

The system allows administrators to publish projects, manage tenders, review contractor quotations, compare bids, award contracts, generate reports, and monitor bidding activities through an interactive dashboard.

Contractors can register, create and manage their profiles, explore available projects, submit bids, track bid status, and receive notifications.

The application provides a transparent and efficient platform for managing construction project bidding processes.

---

# 🌐 Live Application

The project is deployed successfully on Render.

🔗 **Live URL:**  
https://vendorbid-1.onrender.com

---

# ✨ Features

## 👨‍💼 Admin Module

### Authentication
- Secure Admin Login
- Role-based access control

### Dashboard
- Total Projects Overview
- Total Contractors
- Total Bids
- Awarded Contracts Statistics
- Analytics Dashboard

### Project Management
- Create New Projects
- Update Project Details
- Delete Projects
- Manage Tender Information
- View Available Projects

### Bid Management
- View Submitted Contractor Bids
- Compare Contractor Quotations
- Analyze Bid Details
- Select Suitable Contractor

### Contract Management
- Award Contracts
- Track Awarded Projects
- Manage Contract Information

### Notifications & Reports
- Send Notifications
- Generate Reports
- Monitor Bidding Activities

---

# 👷 Contractor Module

## Authentication
- Contractor Registration
- Secure Login
- Logout System

## Contractor Dashboard
- View Dashboard Statistics
- Manage Account Information

## Profile Management
- Update Personal Details
- Maintain Company Profile
- Manage Contractor Information

## Project Module
- View Available Projects
- Check Tender Details
- Submit Project Bids

## Bid Management
- Enter Bid Amount
- Provide Completion Timeline
- Add Proposal Description
- Add Previous Experience
- View Bid History
- Track Bid Status

## Notifications
- Receive Project Updates
- View Award Notifications

---

# 🛠 Technology Stack

## Frontend

- HTML5
- CSS3
- Bootstrap 5
- JavaScript
- Font Awesome

## Backend

- PHP 8+
- CodeIgniter 4 Framework

## Database

- MySQL 8+

## Development Server

- Apache Server
- XAMPP

## Deployment

- Render Cloud Platform

---

# 📂 Project Structure

```
VendorBid_New/

│
├── app/
│   ├── Controllers/
│   ├── Models/
│   ├── Views/
│   └── Config/
│
├── public/
│   ├── assets/
│   ├── css/
│   ├── js/
│   └── images/
│
├── system/
│
├── vendor/
│
├── writable/
│
├── sql/
│   └── vendorbid_full.sql
│
├── spark
│
├── composer.json
│
├── .env
│
└── README.md

```

---

# 💻 Software Requirements

Before running the project, install:

- PHP 8.2 or above
- Composer
- MySQL 8+
- CodeIgniter 4
- XAMPP
- Web Browser

---

# ⚙️ Installation Guide

## Step 1: Clone or Download Project

Clone repository:

```bash
git clone <repository-url>
```

OR

Download ZIP file and extract it.

---

## Step 2: Open Project Directory

Open terminal inside project folder.

```bash
cd VendorBid_New
```

---

## Step 3: Install Composer Dependencies

Run:

```bash
composer install
```

This will install all required CodeIgniter packages.

---

## Step 4: Generate Encryption Key

Run:

```bash
php spark key:generate
```

---

## Step 5: Create Database

Open MySQL / phpMyAdmin.

Create database:

```sql
vendorbid
```

---

## Step 6: Import Database

Import SQL file:

```
sql/vendorbid_full.sql
```

You can use:

- phpMyAdmin
- MySQL Workbench

---

# 🔐 Database Configuration

Open `.env` file and update:

```env
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'


database.default.hostname = localhost
database.default.database = vendorbid
database.default.username = root
database.default.password = root123
database.default.DBDriver = MySQLi
database.default.port = 3306
```

Update username and password according to your MySQL configuration.

---

# ▶️ Running The Project

Start CodeIgniter development server:

```bash
php spark serve
```

OR using XAMPP PHP:

```bash
C:\xampp\php\php.exe spark serve
```

Open browser:

```
http://localhost:8080
```

---

# 🔑 Default Login Credentials

## Admin Login

```
Email:
admin@vendorbid.com

Password:
Admin@123
```

---

## Contractor Login

```
Email:
contractor@vendorbid.com

Password:
Admin@123
```

---

# 🔄 Running Project Again

Navigate to project folder:

```bash
cd C:\Users\asus\Documents\project\VendorBid_New
```

Start server:

```bash
C:\xampp\php\php.exe spark serve
```

Open:

```
http://localhost:8080
```

To stop server:

```
CTRL + C
```

---

# 🌍 Deployment Information

## Production Deployment

The application is deployed on:

### Render Cloud Platform

Live Link:

```
https://vendorbid-1.onrender.com
```

Deployment includes:

- CodeIgniter 4 Application
- PHP Runtime
- MySQL Database Connection
- Environment Configuration

---

# 🚀 Deployment Steps

1. Create hosting account
2. Configure PHP environment
3. Setup MySQL database
4. Import SQL file
5. Upload project source code
6. Install Composer dependencies
7. Configure environment variables
8. Update application URL
9. Start production server
10. Access deployed application

---

# 🧩 Required PHP Extensions

Enable the following PHP extensions:

```
intl
mbstring
mysqlnd
curl
fileinfo
gd
json
```

These extensions are required for proper CodeIgniter and database functionality.

---

# 🔮 Future Enhancements

Future improvements planned:

- Online Payment Integration
- Email Verification System
- SMS Notifications
- Live Tender Tracking
- Document Upload Management
- Digital Signature Support
- AI-Based Bid Recommendation
- Automated Contractor Ranking
- Advanced Analytics Dashboard

---

# 👩‍💻 Developed By

## Bhumika Patil

Master of Computer Applications (MCA)

Nashik, Maharashtra, India

---

# 📜 License

This project is developed for educational and academic purposes.
