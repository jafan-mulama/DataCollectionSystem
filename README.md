# Data Collection System

A comprehensive web application for managing questionnaires with role-based access and dynamic response tracking.

## Features

- Role-based access control (Admin, Lecturer, Student)
- Questionnaire creation and management
- Dynamic response tracking
- Custom styling with plain CSS
- Laravel Breeze authentication

## Requirements

- PHP >= 8.0
- MySQL >= 5.7
- Composer

## Installation

1. Clone the repository
```bash
git clone <repository-url>
```

2. Install PHP dependencies
```bash
composer install
```

3. Configure environment variables
```bash
cp .env.example .env
php artisan key:generate
```

4. Update .env with your database credentials
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Run migrations and seeders
```bash
php artisan migrate --seed
```

## Test Accounts

### Admin
- Email: admin@example.com
- Password: admin123

### Lecturers
- Email: lecturer1@example.com
- Password: lecturer123
- Email: lecturer2@example.com
- Password: lecturer123

### Students
- Email: student1@example.com
- Password: student123
- Email: student2@example.com
- Password: student123
- Email: student3@example.com
- Password: student123

## User Roles and Permissions

### Admin
- Manage users (create, edit, delete)
- View system statistics
- Full system oversight

### Lecturer
- Create and manage questionnaires
- View questionnaire responses
- Access lecturer-specific dashboard

### Student
- View available questionnaires
- Submit questionnaire responses
- View own responses
- Access student-specific dashboard

## Directory Structure

```
DataCollectionSystem/
├── app/
│   ├── Http/
│   │   ├── Controllers/    # Application controllers
│   │   └── Middleware/     # Custom middleware
│   └── Models/            # Eloquent models
├── database/
│   ├── migrations/        # Database migrations
│   └── seeders/          # Database seeders
├── resources/
│   └── views/            # Blade templates
└── routes/
    └── web.php           # Web routes
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is licensed under the MIT License.
