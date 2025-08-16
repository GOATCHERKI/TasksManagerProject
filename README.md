# Tasks Manager

A simple web application for managing tasks, built with Laravel.

## Features

-   Create, edit, and delete tasks
-   Search tasks by title or description
-   Assign order and status to each task
-   User-friendly interface

## Getting Started

### Prerequisites

-   PHP >= 8.0
-   Composer
-   Node.js & npm
-   A database (e.g., MySQL, SQLite)

### Installation

1. **Clone the repository:**

    ```bash
    git clone https://github.com/GOATCHERKI/Tasks-Manager.git
    cd Tasks-Manager
    ```

2. **Install PHP dependencies:**

    ```bash
    composer install
    ```

3. **Install Node dependencies:**

    ```bash
    npm install
    ```

4. **Copy and configure your environment:**

    ```bash
    cp .env.example .env
    ```

    Edit `.env` and set your database and other environment variables.

5. **Generate application key:**

    ```bash
    php artisan key:generate
    ```

6. **Run migrations:**

    ```bash
    php artisan migrate
    ```

7. **Build frontend assets:**

    ```bash
    npm run build
    ```

8. **Start the development server:**
    ```bash
    php artisan serve
    ```

Visit [http://localhost:8000](http://localhost:8000) in your browser.

## Project Structure

-   `app/Http/Controllers/TaskController.php` — Handles all task-related logic.
-   `app/Models/Task.php` — Eloquent model for tasks.
-   `resources/views/tasks/` — Blade templates for task views (list, create, edit).
-   `routes/web.php` — Web routes for the application.

## Usage

-   **Create Task:** Add a new task with title, description, order, and status.
-   **Edit Task:** Update task details.
-   **Delete Task:** Remove a task from the list.
-   **Search:** Use the search bar to filter tasks by title or description.
