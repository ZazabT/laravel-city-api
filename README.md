# Laravel City API

A professional Laravel REST API implementing CRUD operations for a City resource using JSON file-based storage (no database required).

## Features

- Full CRUD operations (Create, Read, Update, Delete)
- JSON file-based persistence (`storage/app/cities.json`)
- Request validation with custom rules
- API Resources for consistent JSON responses
- Unique validation (prevents duplicate city+country combinations)
- Professional code structure (Services, Requests, Resources)

## Installation

1. Clone the repository
```bash
git clone https://github.com/ZazabT/laravel-city-api.git
cd laravel-city-api
```

2. Install dependencies
```bash
composer install
```

3. Copy environment file
```bash
cp .env.example .env
```

4. Generate application key
```bash
php artisan key:generate
```

5. Start the development server
```bash
php artisan serve
```

The API will be available at `http://localhost:8000`

## API Endpoints

Base URL: `http://localhost:8000/api`

### 1. List All Cities
**GET** `/api/cities`

**Response:**
```json
{
    "data": [
        {
            "id": "9d3e4f5a-6b7c-8d9e-0f1a-2b3c4d5e6f7a",
            "name": "Tokyo",
            "country": "Japan",
            "created_at": "2025-12-23T18:00:00+00:00",
            "updated_at": "2025-12-23T18:00:00+00:00"
        }
    ]
}
```

### 2. Create a City
**POST** `/api/cities`

**Request Body:**
```json
{
    "name": "Tokyo",
    "country": "Japan"
}
```

**Response:** `201 Created`
```json
{
    "data": {
        "id": "9d3e4f5a-6b7c-8d9e-0f1a-2b3c4d5e6f7a",
        "name": "Tokyo",
        "country": "Japan",
        "created_at": "2025-12-23T18:00:00+00:00",
        "updated_at": "2025-12-23T18:00:00+00:00"
    }
}
```

### 3. Get a Single City
**GET** `/api/cities/{id}`

**Response:** `200 OK`
```json
{
    "data": {
        "id": "9d3e4f5a-6b7c-8d9e-0f1a-2b3c4d5e6f7a",
        "name": "Tokyo",
        "country": "Japan",
        "created_at": "2025-12-23T18:00:00+00:00",
        "updated_at": "2025-12-23T18:00:00+00:00"
    }
}
```

### 4. Update a City
**PATCH** `/api/cities/{id}`

**Request Body:** (partial update supported)
```json
{
    "name": "Osaka"
}
```

**Response:** `200 OK`
```json
{
    "data": {
        "id": "9d3e4f5a-6b7c-8d9e-0f1a-2b3c4d5e6f7a",
        "name": "Osaka",
        "country": "Japan",
        "created_at": "2025-12-23T18:00:00+00:00",
        "updated_at": "2025-12-23T18:30:00+00:00"
    }
}
```

### 5. Delete a City
**DELETE** `/api/cities/{id}`

**Response:** `204 No Content`

## Validation Rules

### Creating a City
- `name`: required, string, 2-255 characters
- `country`: required, string, 2-255 characters
- Unique combination of `name` + `country` (case-insensitive)

### Updating a City
- `name`: optional, string, 2-255 characters
- `country`: optional, string, 2-255 characters
- Unique combination validation (excluding current city)

## Testing with Postman

### Example: Create Multiple Cities

**1. New York, USA**
```json
{
    "name": "New York",
    "country": "USA"
}
```

**2. Tokyo, Japan**
```json
{
    "name": "Tokyo",
    "country": "Japan"
}
```

**3. Addis Ababa, Ethiopia**
```json
{
    "name": "Addis Ababa",
    "country": "Ethiopia"
}
```

### Example: Update a City
First, get a city ID from the list, then:

**PATCH** `http://localhost:8000/api/cities/{id}`
```json
{
    "name": "Updated City Name"
}
```

### Example: Delete a City
**DELETE** `http://localhost:8000/api/cities/{id}`

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       └── CityController.php      # API endpoints
│   ├── Requests/
│   │   ├── StoreCityRequest.php        # Create validation
│   │   └── UpdateCityRequest.php       # Update validation
│   └── Resources/
│       └── CityResource.php            # JSON response formatting
└── Services/
    └── CityService.php                 # Business logic & storage
```

## Error Responses

### 404 Not Found
```json
{
    "message": "City not found"
}
```

### 422 Validation Error
```json
{
    "message": "The name field is required. (and 1 more error)",
    "errors": {
        "name": ["The city name is required."],
        "country": ["The country name is required."]
    }
}
```

### Duplicate Entry
```json
{
    "message": "The name field is invalid.",
    "errors": {
        "name": ["This city already exists in this country."]
    }
}
```

## License

Open-sourced software licensed under the MIT license.
