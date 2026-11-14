TRIPNEST - TRAVEL AND HOTEL BOOKING DATABASE
================================================

PROJECT STACK
- HTML5, CSS3, JavaScript
- PHP 8+ (backend)
- MySQL / MariaDB
- XAMPP (local server)

SETUP (WINDOWS + XAMPP)
1. Install XAMPP.
2. Copy the entire "tripnest" folder into C:\xampp\htdocs\
3. Open XAMPP Control Panel and start Apache and MySQL.
4. Open http://localhost/phpmyadmin
5. Select the Import tab and import database.sql from this project folder.
   Or open database.sql in the SQL tab and run it.
6. Check config/db.php. Default XAMPP settings are:
   host=localhost, database=tripnest_db, username=root, password empty.
   If your MySQL password is different, update it there.
7. Open http://localhost/tripnest/

FEATURES INCLUDED
- Responsive TripNest landing page
- Destination cards and hotel search
- Hotel details and room listings
- User registration with password hashing
- User login/logout using PHP sessions
- Room booking request and total price calculation
- My Bookings page
- Admin dashboard summary and recent bookings
- MySQL schema with 7 related tables
- Sample destinations, hotels and rooms

ADMIN ACCOUNT
For safety, the project does not include a hard-coded admin password.
1. Register a normal account through the website.
2. In phpMyAdmin, open tripnest_db > users > SQL.
3. Run this query, replacing the email with the account you registered:
   UPDATE users SET role='admin' WHERE email='your-email@example.com';
4. Log out and log in again. The Admin link will appear.
5. Open http://localhost/tripnest/admin/

IMPORTANT PROJECT NOTES
- This is an academic demonstration, not a production booking platform.
- The booking form records a pending booking and a pending "Pay at property" payment.
  No real payment gateway is connected.
- The sample hotel photos are loaded from Unsplash, so internet access is needed for them.
- Before real-world deployment, add CSRF protection, stronger validation, room inventory
  locking/availability checks, booking cancellation rules, secure admin management,
  HTTPS, and a real payment provider.
- Do not upload database credentials or real user data to a public repository.

PROJECT TABLES
users, destinations, hotels, rooms, bookings, payments, reviews
