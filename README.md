# Yohannes Finance Management System

A custom financial management system for Yohannes Hoveniersbedrijf B.V. designed to handle client management, project tracking, invoicing, and financial reporting.

## Features

- Client management with company details and contact information
- Project tracking with hourly rates
- Invoice generation with professional PDF output
- Financial reporting and dashboard
- User authentication and authorization

## Technology Stack

- Laravel PHP Framework
- SQLite/MySQL Database
- Blade Templating
- Bootstrap CSS Framework

## Installation

1. Clone the repository
```
git clone https://github.com/Eyobiel-12/yoha-FINA.git
```

2. Install dependencies
```
composer install
npm install && npm run dev
```

3. Set up environment
```
cp .env.example .env
php artisan key:generate
```

4. Configure database in .env file

5. Run migrations and seed database
```
php artisan migrate --seed
```

6. Start the server
```
php artisan serve
```

## License

This project is proprietary software developed for Yohannes Hoveniersbedrijf B.V.
