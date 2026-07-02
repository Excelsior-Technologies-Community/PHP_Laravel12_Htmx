# PHP_Laravel12_Htmx

## Introduction

PHP_Laravel12_Htmx is a modern web application built using Laravel 12 and HTMX. The project demonstrates how to create dynamic and reactive user interfaces without writing heavy JavaScript.

Users can add and delete tasks instantly, with changes reflected in the UI without page reloads, thanks to HTMX partial rendering.

This project showcases:

- Laravel 12’s MVC architecture

- Blade templating and partial views

- HTMX integration for reactive front-end updates

- Tailwind CSS for clean, modern UI design

---

## Project Overview

The HTMX Task Manager is designed to teach how to build responsive and interactive applications using Laravel and HTMX.

Key features of the project include:

- Task Management: Users can create and delete tasks dynamically.

- Partial Rendering: Only the task list updates on CRUD operations, reducing page reloads.

- Clean UI: Modern responsive layout with Tailwind CSS, including form input, task cards, and a footer.

- Lightweight JavaScript: Uses HTMX for backend communication directly via HTML attributes, keeping the frontend code minimal.

- Scalable Structure: Organized folder structure with layouts, partials, and MVC components for maintainability.

---

## Requirements

- PHP >= 8.1

- Composer

- Laravel 12

- Node.js & NPM

- MySQL

---

## Step 1: Create Laravel 12 Project

```bash
composer create-project laravel/laravel PHP_Laravel12_Htmx "12.*"
```
Move into project:

```bash
cd PHP_Laravel12_Htmx
```

---

## Step 2: Install HTMX Package

```bash
composer require mauricius/laravel-htmx
```

- This package automatically registers service provider (Laravel 12 supports auto-discovery)

---

## Step 3: Setup Environment

Update .env file:

```.env
DB_DATABASE=laravel12_htmx
DB_USERNAME=root
DB_PASSWORD=
```
Run migrations:

```bash
php artisan migrate
```

---

## Step 4: Add HTMX CDN 

File: resources/views/layouts/app.blade.php

```php
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel HTMX Task Manager</title>

    <!-- HTMX -->
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- Header -->
    <header class="w-full bg-white shadow py-6 mb-6">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-3xl font-bold text-gray-800">HTMX Task Manager</h1>
            <p class="text-gray-500 mt-1">Manage your tasks instantly without page reloads</p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 w-full max-w-3xl mx-auto px-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="w-full bg-white shadow py-4 mt-6">
        <div class="max-w-3xl mx-auto text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} Laravel HTMX Task Manager
        </div>
    </footer>

</body>

</html>
```

---

## Step 5: Model & Migration

```bash
php artisan make:model Task -m
```

This single command creates two important files:

- database/migrations/xxxx_create_tasks_table.php

- app/Models/Task.php

---

### Step 6: Migration Table

File: database/migrations/xxxx_create_tasks_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
```

Run:

```bash
php artisan migrate
```

---

## Step 7: Model

File: app/Models/Task.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title'];
}
```
---

## Step 8: Controller

```bash
php artisan make:controller TaskController
```

File: app/Http/Controllers/TaskController.php

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->get();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);

        Task::create([
            'title' => $request->title
        ]);

        // Return updated task list for HTMX
        return $this->renderTasks();
    }

    public function delete(Task $task)
    {
        $task->delete();

        // Return updated task list for HTMX
        return $this->renderTasks();
    }

    private function renderTasks()
    {
        $tasks = Task::latest()->get();

        // If the request comes via HTMX, return the partial
        if (request()->header('HX-Request')) {
            return view('tasks.partials.task-list', compact('tasks'));
        }

        // Fallback for normal request
        return redirect('/');
    }
}
```

---

## Step 9: Routes

File: routes/web.php

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::delete('/tasks/{task}', [TaskController::class, 'delete']);
```
---

## Step 10: Blade View

File: resources/views/tasks/index.blade.php

```blade
@extends('layouts.app')

@section('content')

<!-- Task Input Form -->
<div class="bg-white shadow rounded-lg p-6 mb-6">
    <form 
        hx-post="/tasks" 
        hx-target="#task-list" 
        hx-swap="innerHTML"
        hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
        class="flex gap-3"
    >
        <input 
            type="text" 
            name="title" 
            class="border rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-blue-400"
            placeholder="Enter a new task..."
            required
        >
        <button 
            type="submit" 
            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg transition"
        >
            Add Task
        </button>
    </form>
</div>

<!-- Task List -->
<div id="task-list" class="space-y-3">
    @include('tasks.partials.task-list')
</div>

@endsection
```
---

## Step 11: Partial View

File: resources/views/tasks/partials/task-list.blade.php

```blade
@forelse($tasks as $task)
<div class="flex justify-between items-center bg-white p-4 rounded-lg shadow hover:shadow-md transition">
    <span class="text-gray-800 font-medium">{{ $task->title }}</span>

    <button 
        hx-delete="/tasks/{{ $task->id }}"
        hx-target="#task-list"
        hx-swap="innerHTML"
        hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
        class="text-red-500 hover:text-red-600 font-bold px-3 py-1 border border-red-500 rounded-lg transition"
        title="Delete Task"
    >
        Delete
    </button>
</div>
@empty
<div class="text-gray-500 italic text-center py-6">
    No tasks yet. Add your first task above! 🚀
</div>
@endforelse
```
---

## Step 12: Run Project

```bash
php artisan serve
```
Open in browser:

```bash
http://127.0.0.1:8000
```
---

## Output

<img src="screenshots/Screenshot 2026-03-17 163220.png" width="1000">

<img src="screenshots/Screenshot 2026-03-17 163210.png" width="1000">

---

## Project Structure

```
PHP_Laravel12_Htmx/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── TaskController.php
│   ├── Models/
│   │   └── Task.php
│   └── Providers/
│
├── bootstrap/
│   └── cache/
│
├── config/
│
├── database/
│   ├── migrations/
│       └── 2026_03_17_000000_create_tasks_table.php
│          
│
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php
│   │   ├── tasks/
│   │   │   ├── index.blade.php
│   │   │   └── partials/
│   │   │       └── task-list.blade.php
│   │   └── welcome.blade.php  # Optional landing page
│   └── css/                 # Optional custom CSS files
│
├── routes/
│   └── web.php
│
├── storage/
│   ├── app/
│   ├── framework/
│   └── logs/
│
│
├── .env
├── artisan
├── composer.json
└── package.json   # Optional, only if using npm
```

---

Your PHP_Laravel12_Htmx Project is now ready!
