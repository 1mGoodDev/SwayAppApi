## About SwayAppApi

SwayAppApi is a Laravel-based backend that provides various endpoints for a tech community social media application. This API supports features such as authentication, posting, commenting, liking, following, searching, and user profile management.

### Main Features

- User registration & login (JWT Token)
- CRUD for posts (create, read, update, delete)
- Commenting & liking posts
- Follow & unfollow other users
- Search history
- User profile management

> **Note:** Notification feature is not available in this API.

---

## Folder Structure

```
techMedApi/
├── app/
│   ├── Filament/           # Admin panel & resource management
│   ├── Http/
│   │   ├── Controllers/    # Main API controllers
│   │   └── Resources/      # API response resources
│   ├── Models/             # Eloquent models
│   └── Providers/          # Service providers
├── config/                 # Application configuration
├── database/
│   ├── factories/          # Factories for testing
│   ├── migrations/         # Database migrations
│   └── seeders/            # Initial data seeders
├── public/                 # Public assets
├── resources/              # Frontend views & assets
├── routes/
│   ├── api.php             # API endpoint routing
│   └── web.php             # Web routing
├── tests/                  # Testing
├── .env                    # Environment configuration
├── composer.json           # PHP dependencies
└── README.md               # Documentation
```

---

## How to Use

1. **Clone the repository & install dependencies**
   ```bash
   git clone <repo-url>
   cd techMedApi
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

2. **Configure environment**
   - Edit the `.env` file and adjust your database configuration.
   - Run migrations & seeders:
     ```bash
     php artisan migrate --seed
     ```

3. **Run the local server**
   ```bash
   php artisan serve
   ```
   The API will be available at `http://localhost:8000`

4. **Testing the API**
   - Use Postman, Insomnia, or similar tools to try the endpoints.
   - Main endpoints can be found in the `routes/api.php` file.

---

## Authentication

- Use the `/api/login` endpoint to log in and obtain a token.
- Include the token in the header of every request that requires authentication:
  ```
  Authorization: Bearer <token>
  ```

---

## Example Endpoints & Responses

### 1. Login

**Request:**
```http
POST /api/login
Content-Type: application/json

{
  "email": "user@email.com",
  "password": "password"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Login successful",
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOi..."
}
```

### 2. Get Posts List

**Request:**
```http
GET /api/posts
Authorization: Bearer <token>
```

**Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "user_id": 2,
      "content": "This is the first post",
      "created_at": "2024-05-04T10:00:00Z",
      "likes_count": 5,
      "comments_count": 2
    }
  ]
}
```

### 3. Create a Post

**Request:**
```http
POST /api/posts
Authorization: Bearer <token>
Content-Type: application/json

{
  "content": "This is a new post"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Post created successfully",
  "data": {
    "id": 2,
    "content": "This is a new post",
    "user_id": 1,
    "created_at": "2024-05-04T11:00:00Z"
  }
}
```

---

## Important Notes

- All sensitive endpoints require JWT authentication.
- Ensure your request fields match the documentation.
- API responses use JSON format.
- For file uploads (e.g., profile picture), use multipart/form-data format.
- Rate limiting and input validation are implemented for security.

---

## Full Documentation

For a complete list of endpoints and parameter details, please check the `routes/api.php` file or use tools like Postman to explore the API.
