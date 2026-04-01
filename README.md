#  Task Management API (Laravel)

##  Overview

This is a Task Management API built using Laravel and MySQL.
It allows users to create, view, update, and delete tasks, as well as generate a daily report summarizing tasks by priority and status.

The API follows RESTful principles and enforces business rules such as status progression and deletion restrictions.

---

##  Tech Stack

* Laravel (PHP Framework)
* MySQL Database
* Composer
* Postman / cURL (for testing)

---

##  Setup Instructions (Run Locally)

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/task-management-api.git
cd task-management-api
```

---

### 2. Install Dependencies

```bash
composer install
```

---

### 3. Configure Environment File

Copy the example `.env` file:

```bash
cp .env.example .env
```

Update database settings in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=
```

---

### 4. Generate Application Key

```bash
php artisan key:generate
```

---

### 5. Run Migrations and Seeders

```bash
php artisan migrate --seed
```

---

### 6. Start the Development Server

```bash
php artisan serve
```

Server will run at:

```
http://127.0.0.1:8000
```

---

##  API Endpoints

### 🔹 1. Create Task

```
POST /api/tasks
```

---

### 🔹 2. List Tasks

```
GET /api/tasks
```

Optional filter:

```
GET /api/tasks?status=pending
```

---

### 🔹 3. Update Task Status

```
PATCH /api/tasks/{id}/status
```

---

### 🔹 4. Delete Task

```
DELETE /api/tasks/{id}
```

---

### 🔹 5. Daily Report (Bonus)

```
GET /api/tasks/report?date=YYYY-MM-DD
```

---

##  Example API Requests

### 🔹 Create Task

```json
POST /api/tasks
{
  "title": "Complete assignment",
  "priority": "high",
  "due_date": "2026-04-02"
}
```

---

### 🔹 Update Task Status

```json
PATCH /api/tasks/1/status
{
  "status": "in_progress"
}
```

---

### 🔹 Get Daily Report

```
GET /api/tasks/report?date=2026-04-01
```

---

##  Business Rules Implemented

* Task title must be unique per `due_date`
* `due_date` must be today or later
* Priority must be one of: `low`, `medium`, `high`
* Status progression:

  * `pending → in_progress → done`
* Skipping or reverting status is not allowed
* Only tasks with status `done` can be deleted
* Tasks are sorted by:

  * Priority (high → low)
  * Due date (ascending)

---

##  Daily Report Format

```json
{
  "date": "2026-03-28",
  "summary": {
    "high": {
      "pending": 2,
      "in_progress": 1,
      "done": 0
    },
    "medium": {
      "pending": 1,
      "in_progress": 0,
      "done": 3
    },
    "low": {
      "pending": 0,
      "in_progress": 0,
      "done": 1
    }
  }
}
```

---

##  Deployment Instructions

### Option 1: Railway

1. Create a Railway account
2. Connect your GitHub repository
3. Add a MySQL database plugin
4. Configure environment variables (`.env`)
5. Deploy the application

---

### Option 2: Render

1. Create a Render account
2. Create a new Web Service
3. Connect your GitHub repository
4. Set environment variables
5. Deploy the application

---

##  Testing

You can test the API using Postman or cURL.

### Example cURL Request

```bash
curl http://127.0.0.1:8000/api/tasks
```

---

##  How to Run Quickly

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

---

## 👨 Author

Reagan Chesa Chibole
