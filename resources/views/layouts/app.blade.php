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