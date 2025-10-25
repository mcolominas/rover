# 🚀 Mars Rover API (Backend)

## 🚀 Requirements

Make sure the following dependencies are installed on your system before setting up the project:

### 🧩 Core Requirements
- **PHP** ≥ 8.2  
- **Composer** ≥ 2.x
- **Git**

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

> 🧠 You can check your PHP extensions by running:
```
php -m
```

If any of the required extensions are missing, enable them in your php.ini file.

---

## ⚙️ Installation Steps
1. **Clone the repository**
    ```shell
    git clone https://github.com/mcolominas/rover.git
    cd ./backend
    ```

2. **Run the automated setup script**
The project includes a Composer script that performs all the initial setup steps automatically.
Run the following command:
    ```shell
    composer run setup
    ```

---

## ▶️ Running the Application
```shell
php artisan serve
```

---

## ⚙️ Endpoints

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
```
{
  "error": 1003,
  "message": "Failed to place an obstacle at the indicated position"
}
```

HTTP 409:
```
{
  "error": 1004,
  "message": "Obstacle limit exceeded"
}
```

---

### 2. Launch a rover

**Route:** POST /rovers/launch

**Description:**  
Launches a rover at an initial position within an existing planet.

**Required Parameters:**
```
{
  "planet_id": 1,
  "x": 2,
  "y": 3,
  "direction": "N"
}
```

**Successful Response (201):**
```
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
```
{
  "error": 1005,
  "message": "A rover already exists at the indicated position"
}
```

HTTP 422:
```
{
  "error": 1001,
  "message": "The initial position is invalid"
}
```

HTTP 422:
```
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
```
{
  "commands": "FFF"
}
```

**Successful Response (200):**
```
{
  "message": "Commands executed successfully",
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
```
{
  "error": 1002,
  "message": "Obstacle detected during execution",
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
```
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
```
php artisan test
```

### Types of Tests
- **Feature Tests:** Verify main routes and HTTP responses.  
- **Unit Tests:** Check internal logic, obstacle detection, and command execution.  

All test responses use the defined JSON format to ensure consistency across the API.