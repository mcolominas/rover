# 🚀 Mars Rover API (Backend)

## 🚀 Requirements

Make sure the following dependencies are installed on your system before setting up the project:

### 🧩 Core Requirements
- **PHP** ≥ 8.2  
- **Composer** ≥ 2.x

### 🧱 PHP Extensions
Ensure the following PHP extensions are enabled:
- pdo
- sqlite3
- pdo_sqlite
- openssl
- mbstring
- tokenizer
- xml
- ctype
- json
- bcmath

> 🧠 Check your PHP extensions with:
    ```
    php -m
    ```

---

## ⚙️ Installation

**Run the automated setup script**
```sh
composer run setup
```

---

## 🌍 Planet Environment Variables

These variables exist in the `.env` file and can be adjusted for your project:

```
PLANET_WIDTH=20
PLANET_HEIGHT=20
PLANET_MIN_OBSTACLES=100
PLANET_MAX_OBSTACLES=200
```

---

## ▶️ Running the Application
```sh
php artisan serve
```

---

## ⚙️ API Endpoints

### 1. Create a planet

**Route:** POST /planet

**Description:**  
Creates a new planet where rovers can be launched.

**Parameters:**  
No data is required in the request body.

**Successful Response (201):**
```json
{
  "message": "Planet created successfully.",
  "data": {
    "id": 1,
    "width": 5,
    "height": 5
  }
}
```

**Possible Error Responses:**

HTTP 409:
```json
{
  "error": 1003,
  "message": "Too many attempts ({$attempts}) generating obstacles. Planet might be too small."
}
```

HTTP 409:
```json
{
  "error": 1004,
  "message": "Cannot generate more obstacles ({$max}) than available cells ({$totalCells})."
}
```

---

### 2. Launch a rover

**Route:** POST /rovers/launch

**Description:**  
Launches a rover at an initial position within an existing planet.

**Required Parameters:**
```json
{
  "planet_id": 1,
  "x": 2,
  "y": 3,
  "direction": "N"
}
```

**Successful Response (201):**
```json
{
  "message": "Rover launched successfully.",
  "data": {
    "id": 1,
    "planet_id": 1,
    "position": {
      "x": 2,
      "y": 3
    },
    "direction": "N"
  }
}
```

**Possible Error Responses:**

HTTP 422:
```json
{
  "error": 1005,
  "message": "A rover already exists on this planet."
}
```

HTTP 422:
```json
{
  "error": 1001,
  "message": "Invalid position for rover."
}
```

HTTP 422:
```json
{
  "error": 1000,
  "message": "Validation error.",
  "errors": {
    "x": ["The x field must be an integer greater than or equal to 0"],
    "direction": ["The direction provided is not valid"]
  }
}
```

---

### 3. Execute commands on a rover

**Route:** POST /rovers/{rover}/commands

**Description:**  
Executes a sequence of commands on an existing rover.

**Required Parameters:**
```json
{
  "commands": "FFF"
}
```

**Successful Response (200):**
```json
{
  "message": "Commands executed successfully.",
  "data": {
    "position": {
      "x": 3,
      "y": 6
    },
    "direction": "N",
    "path": [
        {
            "position": {"x": 2, "y": 4},
            "direction": "N",
            "movement": "F"
        },
        {
            "position": {"x": 2, "y": 5},
            "direction": "N",
            "movement": "F"
        },
        {
            "position": {"x": 3, "y": 6},
            "direction": "N",
            "movement": "F"
        }
    ]
  }
}
```

**Possible Error Responses:**

HTTP 422:
```json
{
  "error": 1002,
  "message": "Obstacle detected at position (3, 6).",
  "coordinates": {"x":3,"y":6},
  "path": [
        {
            "position": {"x": 2, "y": 4},
            "direction": "N",
            "movement": "F"
        },
        {
            "position": {"x": 2, "y": 5},
            "direction": "N",
            "movement": "F"
        }
    ]
}
```

HTTP 422:
```json
{
  "error": 1000,
  "message": "Validation error.",
  "errors": {
    "commands": ["Commands can only contain F, L, R"]
  }
}
```

---

## 🧪 Testing

### Run Tests
```sh
php artisan test
```

### Types of Tests
- **Feature Tests:** Verify main routes and HTTP responses.  
- **Unit Tests:** Check internal logic, obstacle detection, and command execution.  

---

## 🏭 Production Preparation

1. **Run the automated setup**
    ```sh
    composer run setup
    ```

2. **Remove development dependencies**
    ```sh
    composer install --no-dev --optimize-autoloader
    ```

3. **Optimize the application**
    ```sh
    php artisan optimize
    ```

4. **Set up server and permissions**
- Point Apache/Nginx to the `public/` folder.
- Ensure `storage/` and `bootstrap/cache/` are writable.
- Set correct permissions for `storage/logs/`.

5. **Security**
- Enable HTTPS.
- Restrict access to sensitive files (`.env`, `storage`, etc.).
- Configure firewall and request limits as needed.
