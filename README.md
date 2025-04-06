# Kshetri Samaj Website Setup Guide

## Database Configuration

1. **Install MySQL** if not already installed.
2. **Run MySQL client**:
   ```bash
   mysql -u root -p
   ```
3. **Execute setup script**:
   ```bash
   source setup_database.sql
   ```

## Website Setup

1. Place all files in your web server's document root.
2. Ensure PHP and MySQL are properly configured.
3. Update `config.php` with your database credentials if necessary.
4. Access the website through your browser.

## Features

- Dynamic news and events sections
- Membership registration form
- Admin panel for content management

## Troubleshooting

- If PHP files are not executing, ensure your server supports PHP (e.g., Apache or Nginx).
- Check database connection settings in `config.php`.