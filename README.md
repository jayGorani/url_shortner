# URL Shortener

A multi-tenant URL Shortening Platform built with **Laravel 12** and **MySQL**, designed to manage companies, teams, users, and shortened URLs through a structured role-based access control system.

The application provides three levels of access — **Super Admin, Admin, and Member** — with company-level data isolation, secure invitation workflows, URL management, and public redirection.

## Key Features

### User & Role Management
- Three-level role-based access: Super Admin, Admin, and Member.
- Company onboarding through Super Admin invitations.
- Company-scoped user and team management.
- Secure invitation workflows for Admins and Members.
- Role-based authorization using Laravel Policies and Middleware.

### Invitation Management
- Secure, hashed invitation tokens.
- Seven-day invitation expiration.
- Single-use invitation links.
- Resending invitations with token rotation and extended expiration.
- Invitation revocation for pending invitations.
- Transactional company and user creation upon invitation acceptance.

### URL Management
- Generate unique, random eight-character short codes.
- Database-level uniqueness with collision handling and retry logic.
- Public HTTP/HTTPS URL redirection.
- Atomic click-count tracking.
- Company-scoped URL management for Admins.
- Member-specific URL management.
- Search, pagination, and link management.

### Dashboard & Administration
- Global company, user, and URL overview for Super Admin.
- Company-level URL and user management for Admins.
- Personal URL management for Members.
- Responsive interface built with Bootstrap 5.

### Testing
- PHPUnit feature tests covering important application workflows.
- Tests for role-based access and authorization boundaries.
- Coverage of key invitation and URL management scenarios.

## Technology Stack

| Technology | Usage |
|---|---|
| PHP 8.4+ | Backend development |
| Laravel 12 | Application framework |
| MySQL 8+ | Database |
| Blade | Server-side templating |
| Bootstrap 5 | Frontend UI |
| PHPUnit | Automated testing |

## Architecture & Code Organization

The application follows Laravel conventions with a separation of responsibilities to keep the code maintainable and scalable.

- **Controllers:** Handle HTTP requests and coordinate application workflows.
- **Services:** Encapsulate business logic, including invitation management and short-code generation.
- **Form Requests:** Handle request validation.
- **Middleware:** Enforce role-based access restrictions.
- **Policies:** Manage resource-level authorization.
- **Eloquent Models:** Define database relationships and application data.
- **Database Transactions:** Maintain data consistency during multi-step operations.

## Installation & Setup

This repository contains the application code intended to be integrated into an existing, fresh Laravel 12 installation. It does not include Laravel framework dependencies or generated application files.

### 1. Create a Laravel 12 Project

```bash
composer create-project laravel/laravel url-shortener "^12.0"
cd url-shortener
```

Copy the application files from this repository into the Laravel project, preserving their directory structure.

Keep the Laravel-generated files and directories, including `vendor`, `bootstrap`, `storage`, and the default configuration files.

### 2. Configure Environment Variables

Update your `.env` file with your database and SMTP configuration.

**Database Configuration**

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shorten_urls
DB_USERNAME=root
DB_PASSWORD=your_database_password
```

Create the database before running migrations.

**Mail Configuration**

```dotenv
MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_gmail_app_password
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

Replace the placeholder values with your actual SMTP credentials.

For Gmail, use an App Password instead of your regular Google account password.

### 3. Configure Super Admin Credentials

Super Admin credentials are configurable through the `super_admin` configuration array in `config/app.php`.

```php
'super_admin' => [
    'email'    => env('SUPER_ADMIN_EMAIL', 'superadmin@email.com'),
    'password' => env('SUPER_ADMIN_PASSWORD', 'Super@12345'),
    'name'     => env('SUPER_ADMIN_NAME', 'Shorten URL - Super Admin'),
],
```

You can customize these credentials using the corresponding environment variables:

```dotenv
SUPER_ADMIN_EMAIL=superadmin@email.com
SUPER_ADMIN_PASSWORD=ChangeThisPassword
SUPER_ADMIN_NAME="Shorten URL - Super Admin"
```

The Super Admin account is created through `SuperAdminSeeder`.

**Security note:** Change the default credentials before deploying the application to any shared or production environment.

### 4. Run Database Migrations & Seeder

Ensure that the default Laravel `users` migration is available, as the application extends the users table with `company_id` and `role` columns.

Run the following commands:

```bash
php artisan migrate
php artisan db:seed --class=SuperAdminSeeder
```

### 5. Start the Development Server

```bash
php artisan serve
```

Open the application in your browser:

```text
http://127.0.0.1:8000
```

## Application Workflow

The application follows a company-based multi-tenant workflow.

1. **Super Admin:** Logs in and creates an Admin invitation by providing a new company name and the Admin's email address.
2. **Company Onboarding:** The invited Admin opens the invitation link and sets a password. The company and Admin account are created transactionally after successful acceptance.
3. **Admin Management:** Company Admins can invite additional Admins and Members within their own company.
4. **URL Management:** Admins can manage company URLs, while Members can manage only their own URLs.
5. **Super Admin Access:** Super Admins can view global company, user, and URL information but cannot create short URLs.
6. **Public Redirection:** Visitors can access shortened URLs without authentication using the generated short code.

## Invitation & Email Configuration

The application uses SMTP to send invitation emails.

Make sure your SMTP credentials and sender information are correctly configured in `.env`.

Invitation lifecycle:

- Invitations remain valid for seven days.
- Resending an invitation generates a new token and extends its expiration.
- Revoking an invitation removes the pending invitation record.
- Accepted invitations cannot be reused.

## Running Tests

Run the PHPUnit test suite using:

```bash
php artisan test
```

Tests use Laravel's configured testing environment.

For reliable test execution, configure a dedicated testing database or SQLite database through `phpunit.xml` or `.env.testing`.

**Note:** Tests should be executed in a properly configured Laravel 12 environment. Test execution results are not claimed as verified by this README.

## Project Structure

The application follows Laravel's standard directory structure, with additional organization for business logic and authorization.

```text
app/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Mail/
├── Models/
├── Policies/
└── Services/

database/
├── migrations/
└── seeders/

resources/
└── views/

routes/
└── web.php

tests/
└── Feature/
```

## License

This project is currently provided for evaluation and demonstration purposes.
