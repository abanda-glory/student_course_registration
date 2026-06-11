# Student Course Registration API

A RESTful API built with Laravel 12 and Laravel Sanctum for managing student course registrations. This API allows students to register accounts, browse courses, and enroll in them.

---

## Built With

- **Laravel 12**
- **PHP 8.2**
- **MySQL**
- **Laravel Sanctum** — API token authentication
- **Swagger/OpenAPI** — API documentation

---

## Requirements

- PHP 8.2 or higher
- Composer 2.x
- MySQL
- XAMPP (or any local server with MySQL)

---

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/your-username/student-registration-api.git
cd student-registration-api
```

### 2. Install dependencies

```bash
composer install
```

### 3. Create your environment file

```bash
cp .env.example .env
```

### 4. Configure your `.env` file

Open `.env` and update the database settings:

```env
APP_NAME="Student Registration API"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=student_registration_api
DB_USERNAME=root
DB_PASSWORD=

L5_SWAGGER_GENERATE_ALWAYS=true
L5_SWAGGER_BASE_PATH=/api
L5_SWAGGER_CONST_HOST=http://127.0.0.1:8000
```

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Create the database

Create a MySQL database named `student_registration_api` via phpMyAdmin or MySQL CLI.

### 7. Run migrations and seeders

```bash
php artisan migrate --seed
```

This will create all tables and seed:

- 5 fake users
- 10 fake courses

### 8. Start the development server

```bash
php artisan serve
```

The API will be available at `http://127.0.0.1:8000`

---

## API Documentation

Swagger documentation is available at:

```
http://127.0.0.1:8000/api/documentation
```

You can view and test all endpoints interactively from there.

---

## Authentication

This API uses **Bearer token authentication** via Laravel Sanctum.

1. Register or login to receive a token
2. Include the token in all protected requests:

```
Authorization: Bearer your_token_here
```

---

## API Endpoints

### Authentication

| Method | Endpoint        | Description                    | Protected |
| ------ | --------------- | ------------------------------ | --------- |
| POST   | `/api/register` | Register a new user            | No        |
| POST   | `/api/login`    | Login and receive token        | No        |
| POST   | `/api/logout`   | Logout and revoke token        | Yes       |
| GET    | `/api/profile`  | Get authenticated user profile | Yes       |

### Courses

| Method | Endpoint                      | Description                     | Protected |
| ------ | ----------------------------- | ------------------------------- | --------- |
| GET    | `/api/courses`                | Get all courses                 | No        |
| GET    | `/api/courses?search=keyword` | Search courses by title or code | No        |
| GET    | `/api/courses/{id}`           | Get a single course             | No        |
| POST   | `/api/courses`                | Create a new course             | Yes       |
| PUT    | `/api/courses/{id}`           | Update a course                 | Yes       |
| DELETE | `/api/courses/{id}`           | Delete a course                 | Yes       |

### Enrollments

| Method | Endpoint                | Description          | Protected |
| ------ | ----------------------- | -------------------- | --------- |
| POST   | `/api/enrollments`      | Enroll in a course   | Yes       |
| GET    | `/api/enrollments`      | View my enrollments  | Yes       |
| DELETE | `/api/enrollments/{id}` | Cancel an enrollment | Yes       |

---

## Request Examples

### Register

```json
POST /api/register
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

### Login

```json
POST /api/login
{
    "email": "john@example.com",
    "password": "password123"
}
```

### Create a Course

```json
POST /api/courses
Authorization: Bearer your_token_here

{
    "title": "Web Development",
    "code": "WEB101",
    "description": "An introduction to web development",
    "credit_hours": 3
}
```

### Enroll in a Course

```json
POST /api/enrollments
Authorization: Bearer your_token_here

{
    "course_id": 1
}
```

---

## HTTP Status Codes

| Code | Meaning                                  |
| ---- | ---------------------------------------- |
| 200  | OK — Request succeeded                   |
| 201  | Created — Resource created successfully  |
| 401  | Unauthorized — Invalid or missing token  |
| 403  | Forbidden — Action not allowed           |
| 404  | Not Found — Resource does not exist      |
| 422  | Unprocessable Entity — Validation failed |
| 429  | Too Many Requests — Rate limit exceeded  |

---

## Project Structure

```
student-registration-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php       # Register, Login, Logout, Profile
│   │   │   ├── CourseController.php     # Course CRUD + Search + Pagination
│   │   │   ├── EnrollmentController.php # Enroll, View, Cancel
│   │   │   └── OpenApiInfo.php          # Swagger API info
│   │   ├── Requests/
│   │   │   ├── RegisterRequest.php      # Registration validation
│   │   │   ├── LoginRequest.php         # Login validation
│   │   │   ├── StoreCourseRequest.php   # Create course validation
│   │   │   ├── UpdateCourseRequest.php  # Update course validation
│   │   │   └── EnrollRequest.php        # Enrollment validation
│   │   └── Resources/
│   │       ├── UserResource.php         # User JSON response shape
│   │       ├── CourseResource.php       # Course JSON response shape
│   │       └── EnrollmentResource.php   # Enrollment JSON response shape
│   ├── Models/
│   │   ├── User.php                     # User model with relationships
│   │   ├── Course.php                   # Course model with relationships
│   │   └── Enrollment.php               # Enrollment model with relationships
│   └── Providers/
│       └── AppServiceProvider.php       # Rate limiter configuration
├── database/
│   ├── factories/
│   │   ├── UserFactory.php              # Fake user data generator
│   │   └── CourseFactory.php            # Fake course data generator
│   ├── migrations/                      # Database table definitions
│   └── seeders/
│       ├── DatabaseSeeder.php           # Master seeder
│       ├── UserSeeder.php               # Seeds 5 users
│       └── CourseSeeder.php             # Seeds 10 courses
└── routes/
    └── api.php                          # All API route definitions
```

---

## Features

- Token-based authentication with Laravel Sanctum
- Full CRUD operations for courses
- Student enrollment system with duplicate prevention
- Form Request validation on all input endpoints
- API Resources for consistent JSON response formatting
- Swagger/OpenAPI documentation
- Course search by title or code
- Pagination — 10 courses per page
- Rate limiting — 5 attempts per minute on login and register
- Cascade deletion — enrollments deleted when user or course is deleted

---

## License

This project was built as an academic internship assessment task.
