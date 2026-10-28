# PHP CRUD – Student Manager

A simple CRUD application in plain PHP and MySQL for managing student records (add, list, edit, delete) with a contact page.

## Features

- Create, read, update and delete students
- PDO prepared statements (protection against SQL injection)
- Server-side validation and output escaping (`htmlspecialchars`)
- Delete accepts POST only, with ID validation and proper HTTP status codes
- Responsive, dependency-free UI
- Contact form using PHPMailer

## Tech Stack

PHP 8+, MySQL, PDO, Composer (PHPMailer), HTML5, CSS3

## Setup

1. **Clone the repository**

   ```bash
   git clone https://github.com/Ankit-Kumar-71/php-crud.git
   cd php-crud
   ```

2. **Install dependencies**

   ```bash
   composer install
   ```

3. **Create the database and table**

   ```bash
   mysql -u <user> -p < database.sql
   ```

4. **Configure credentials**

   ```bash
   cp .env.example .env
   ```

   Then edit `.env` with your MySQL username and password.

5. **Start the app**

   ```bash
   php -S localhost:8000
   ```

   Open [http://localhost:8000](http://localhost:8000).

## Project Structure

```
.
├── index.php      # List students
├── create.php     # Add a student
├── edit.php       # Update a student
├── delete.php     # Delete a student (POST only)
├── contact.php    # Contact form
├── db.php         # PDO connection (reads .env)
└── database.sql   # Database schema
```

## Security Notes

- Never commit `.env`; it is listed in `.gitignore`.
- Planned improvements: CSRF tokens, authentication, pagination.

## Screenshots

_Add screenshots of the list and form pages here._

## Author

Ankit Kumar – [GitHub](https://github.com/Ankit-Kumar-71)
