# Book Borrow Management System

The **Book Borrow Management System** is a web application that allows users to:
- View available books.
- Add new books to the system.
- Borrow books with a token system for 10 days (extendable).
- The application uses **AJAX**, **JSON**, and **MySQL** (via XAMPP) for efficient data handling.

---

## Features

- **View Books**: See a list of all books currently available in the library. The list dynamically updates every 3 seconds using **AJAX**.
- **Add New Books**: Add books to the system using an intuitive interface with fields for title, author, publish date, price, description, and ISBN.
- **Borrow System**: Borrow books using a token system, with an option to extend borrowing for 10 days. Tokens for extended borrowing are displayed in an interactive list.
- **Upcoming Books**: Displays books that will be available soon with a countdown in days. This section updates dynamically via **AJAX**.
- **Database Integration**: All book records are stored and managed in a MySQL database.

---

## Technology Stack

- **Frontend**: HTML, CSS, JavaScript, AJAX, JSON.
- **Backend**: PHP.
- **Database**: MySQL (via XAMPP).

---

## Installation Instructions

Follow these steps to set up the Book Borrow Management System on your local machine:

### Step 1: Install XAMPP
1. Download XAMPP from [apachefriends.org](https://www.apachefriends.org/).
2. Install XAMPP and ensure that the following services are enabled:
   - **Apache** (for the web server).
   - **MySQL** (for the database).

### Step 2: Set Up the Project Folder
1. Copy the project folder (e.g., `Book-Borrow-management`) into the `htdocs` folder of XAMPP.

### Step 3: Configure the Database
1. Open **phpMyAdmin** by navigating to `http://localhost/phpmyadmin` in your browser.
2. Create a new database (e.g., `library_management`).
3. Import the SQL file (`database.sql`) provided in the project folder to set up the required tables and data.

### Step 4: Update Database Configuration
1. Locate the database configuration file in the project folder (e.g., `config.php`).
2. Update the following details:
   ```php
   $host = 'localhost';
   $user = 'root';
   $password = ''; // Leave blank if no password is set for MySQL
   $dbname = 'library_management';
   ```

### Step 5: Run the Application
1. Open a browser and navigate to `http://localhost/Book-Borrow-management`.
2. Explore the features of the system.

---

### WEB PAGE VIEW

![Image](https://github.com/user-attachments/assets/c72e013f-7cdc-42d7-bb62-9da7ea2b5823)

---

## Future Enhancements

1. **User Authentication**:
   - Implement login and registration for students and administrators.
   - Provide role-based access control.

2. **Overdue Notifications**:
   - Send email or SMS reminders for overdue books.

3. **Advanced Search**:
   - Add filters for searching books by genre, author, or publication year.

4. **Mobile Responsiveness**:
   - Optimize the interface for mobile devices.

5. **Analytics Dashboard**:
   - Provide insights into the most borrowed books, user activity, and token usage.

---

Enjoy using the **Book Borrow Management System**!

