# Notes REST API

A robust RESTful API built with Laravel for managing personal notes and dynamic tags. This project includes secure user authentication using Laravel Sanctum and tagging system.

## Tech Stack

- **Framework:** Laravel 12
- **Language:** PHP 8.4+
- **Database:** MySQL

## API Endpoints

**Public Routes:**

- `POST /api/register` - Register a new user
- `POST /api/login` - Login and get Bearer Token

**Protected Routes (Requires Bearer Token):**

- `POST /api/logout` - Logout and revoke token
- `GET /api/notes` - Get all user's notes
- `GET /api/notes/{id}` - Get a specific note
- `POST /api/notes` - Create a new note with tags
- `PATCH /api/notes/{id}` - Update a note and sync tags
- `DELETE /api/notes/{id}` - Delete a note
