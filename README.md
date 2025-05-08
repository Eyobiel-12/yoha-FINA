# Yohannes Finance

Financial management system for Yohannes Hoveniersbedrijf B.V.

## Features

- Client management
- Project tracking
- Invoice generation
- Payment tracking
- PDF invoice export
- Company logo on invoices

## InfinityFree Deployment Instructions

### 1. Sign Up for InfinityFree

1. Go to [InfinityFree](https://app.infinityfree.net/register)
2. Create a new account
3. Log in to your dashboard

### 2. Create a New Hosting Account

1. From the dashboard, click "Add Account"
2. Choose a subdomain or use your own domain
3. Complete the setup process

### 3. Access Control Panel

1. Go to your account dashboard
2. Click "Control Panel" to access the hosting control panel

### 4. Create a MySQL Database

1. In the control panel, find "MySQL Databases"
2. Create a new database and note the:
   - Database name
   - Database username
   - Database password
   - Database host (usually sql.infinityfree.com)

### 5. Upload Files

1. Use FTP or the File Manager in the control panel
2. Upload all files from this project to the public_html folder
3. Make sure the `index.php` file is in the root directory

### 6. Configure .env File

Update the `.env` file with your database details:

```
DB_CONNECTION=mysql
DB_HOST=sql.infinityfree.com
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

### 7. Run Migrations

You may need to manually import the database schema from the SQL files or use SSH if available.

## Local Development

### Requirements

- PHP 8.1+
- Composer
- MySQL

### Setup

1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env` and configure
4. Run `php artisan key:generate`
5. Run `php artisan migrate`
6. Run `php artisan serve`

## License

Copyright (c) 2023 Yohannes Hoveniersbedrijf B.V. All rights reserved.
