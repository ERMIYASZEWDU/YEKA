YEKA INDUSTRIAL COLLEGE WEBSITE (LOCAL XAMPP)

REQUIREMENTS
- Windows PC
- XAMPP with Apache, PHP and MySQL/MariaDB
- Browser and text editor (VS Code recommended)

INSTALL
1. Extract the yeka_college folder to C:\xampp\htdocs\yeka_college
2. Open XAMPP Control Panel and start Apache and MySQL.
3. Open http://localhost/phpmyadmin/
4. Choose Import, select database/yeka_college.sql, and click Import.
   The SQL creates yeka_college_db and its tables.
5. Open http://localhost/yeka_college/setup_admin.php
6. Create a unique admin username and strong password (12+ characters).
7. Immediately delete C:\xampp\htdocs\yeka_college\setup_admin.php after creating the account.
8. Open http://localhost/yeka_college/admin/login.php and sign in.
9. Add a test announcement in English and Amharic; choose Published.
10. Visit http://localhost/yeka_college/news.php and switch language to verify it appears.

PAGES
- http://localhost/yeka_college/
- http://localhost/yeka_college/about.php
- http://localhost/yeka_college/news.php
- http://localhost/yeka_college/contact.php
- http://localhost/yeka_college/admin/login.php

NOTES
- This starter uses a local XAMPP default database connection in config/db.php (root with blank password). Do not use that configuration on a public server.
- Replace sample About/Contact details with verified college information.
- The website is intended for local development. Before public deployment, configure HTTPS, a dedicated database account, backups, upload validation, security headers and a production PHP configuration.

CONTACT FORM AND ADMIN REPLIES
- The public Contact page stores submitted messages in the contact_messages table.
- Admin dashboard shows a count of new contact messages.
- Open Admin dashboard > Contact inbox to read a message and save a reply.
- The reply is saved in the dashboard/database. It is NOT emailed automatically.
- To email replies/notifications, configure an SMTP provider (for example PHPMailer with authenticated SMTP) and a verified college email address.
- If this is an existing installation, import database/upgrade_contact_messages.sql in phpMyAdmin. Do not re-import the full database SQL if you need to preserve existing content.

CONTACT FORM UPDATE
- Replaced the Subject field with Address.
- Added a required Phone number field.
- Admin inbox shows the sender phone number and address.
- For an existing database, import database/upgrade_contact_messages.sql in phpMyAdmin.

FINAL CONTACT DATABASE FIX
- config/db.php now automatically creates the contact_messages table when the project connects to MySQL.
- It also adds missing phone/address/reply columns to older versions of the table without deleting messages.
- database/setup_contact_messages.sql is included as a manual setup option.
- The configured database name remains yeka_college_db. The folder name may be yeka_college3; those names do not have to match.

LANGUAGE SETTINGS
- Both Amharic and English are available; neither language is mandatory.
- The navigation displays both language choices at the same time.
- Visitors can select አማርኛ or English whenever they want.
- Amharic is used only as the initial display for a first visit; visitors can immediately choose English.
