VendorBid – Smart Contractor Bidding & Tender Management System
Overview
VendorBid is a web-based Contractor Bidding and Tender Management System developed using CodeIgniter 4 and MySQL. The application enables administrators to publish projects, manage bids, compare contractor quotations, award contracts, generate reports, and monitor bidding activities through an intuitive dashboard.

Contractors can register, maintain their profiles, view available projects, submit bids, track awards, and receive notifications.

Features
Admin Module
Secure Admin Login
Dashboard with Statistics
Project Management (CRUD)
View Submitted Bids
Compare Contractor Bids
Award Contracts
Notifications
Reports
Analytics Dashboard
Contractor Module
Registration & Login
Dashboard
Profile Management
View Available Projects
Submit Bids
View Bid History
Notifications
Technology Stack
Frontend

HTML5
CSS3
Bootstrap 5
JavaScript
Backend

PHP 8+
CodeIgniter 4
Database

MySQL
Server

Apache (XAMPP)
Project Structure
VendorBid_New/
│
├── app/
├── public/
├── system/
├── vendor/
├── writable/
├── spark
├── composer.json
├── .env
└── README.md
Software Requirements
PHP 8.2 or above
Composer
MySQL 8+
XAMPP
CodeIgniter 4
Web Browser
Installation Guide
Step 1
Clone or Download the project.

git clone <repository-url>
or

Extract the ZIP file.

Step 2
Open Terminal / Command Prompt.

cd VendorBid_New
Step 3
Install Composer dependencies.

composer install
Step 4
Generate the encryption key.

php spark key:generate
Step 5
Create a MySQL database.

Database Name

vendorbid
Step 6
Import

sql/vendorbid_full.sql
using phpMyAdmin or MySQL Workbench.

Step 7
Configure the .env file.

CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = vendorbid
database.default.username = root
database.default.password = root123
database.default.DBDriver = MySQLi
database.default.port = 3306
Update the database credentials according to your local MySQL configuration.

Step 8
Start the application.

php spark serve
or

C:\xampp\php\php.exe spark serve
Step 9
Open

http://localhost:8080
Default Login Credentials
Admin
Email

admin@vendorbid.com
Password

Admin@123
Contractor
Email

contractor@vendorbid.com
Password

Admin@123
Running the Project Again
Whenever you want to run the project again:

cd C:\Users\asus\Documents\project\VendorBid_New
Start the server

C:\xampp\php\php.exe spark serve
Open

http://localhost:8080
Press

CTRL + C
to stop the server.

Deployment
The project can be deployed on:

InfinityFree
000WebHost
Hostinger
cPanel Hosting
Apache Server
Deployment Steps

Create Hosting Account
Create MySQL Database
Import SQL File
Configure .env
Upload Project Files
Update app.baseURL
Launch Website
Future Enhancements
Online Payment Integration
Email Verification
SMS Notifications
Live Tender Tracking
File Versioning
Digital Signature Support
AI-based Bid Recommendation
Developed By
Bhumika Patil

Master of Computer Applications (MCA)

Nashik

License
This project is developed for educational and academic purposes.

json (enabled by default - don't turn it off)
mysqlnd if you plan to use MySQL
libcurl if you plan to use the HTTP\CURLRequest library

