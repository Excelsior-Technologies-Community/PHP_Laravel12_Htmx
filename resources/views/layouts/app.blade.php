<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laravel 12 HTMX Task Manager</title>

    <!-- HTMX -->
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(16px);
        }

        .animate-fade {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body
    class="min-h-screen bg-gradient-to-br from-indigo-100 via-sky-50 to-cyan-100 flex items-center justify-center p-5 overflow-x-hidden">

    <!-- Background Blur -->

    <div
        class="absolute top-0 left-0 w-72 h-72 bg-purple-300 rounded-full blur-3xl opacity-30">
    </div>

    <div
        class="absolute bottom-0 right-0 w-96 h-96 bg-cyan-300 rounded-full blur-3xl opacity-30">
    </div>

    <!-- Toast Container -->

    <div id="toast-container"></div>

    <!-- Main Wrapper -->

    <div class="relative z-10 w-full max-w-4xl">

        <!-- Card -->

        <div
            class="glass shadow-2xl rounded-[35px] overflow-hidden border border-white/40">

            <!-- Header -->

            <div
                class="bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-500 px-8 py-10 text-center text-white relative overflow-hidden">

                <!-- Decorative Circles -->

                <div
                    class="absolute top-0 left-0 w-44 h-44 bg-white/10 rounded-full -translate-x-20 -translate-y-20">
                </div>

                <div
                    class="absolute bottom-0 right-0 w-60 h-60 bg-white/10 rounded-full translate-x-20 translate-y-20">
                </div>

                <!-- Title -->

                <h1 class="text-4xl md:text-5xl font-bold tracking-wide relative z-10">

                    Laravel 12 HTMX Task Manager

                </h1>

                <!-- Subtitle -->

                <p class="mt-4 text-blue-100 text-lg relative z-10">

                    Manage tasks instantly without page reloads ⚡

                </p>

            </div>

            <!-- Body -->

            <div class="p-8 bg-white/70">

                <!-- Validation Errors -->

                @if($errors->any())

                    <div
                        class="animate-fade mb-6 bg-red-100 border border-red-300 text-red-700 px-5 py-4 rounded-2xl shadow-md">

                        <div class="font-semibold mb-2">

                            Please fix the following errors:

                        </div>

                        <ul class="space-y-1 list-disc list-inside">

                            @foreach($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>

                @endif

                <!-- Page Content -->

                @yield('content')

            </div>

        </div>

        <!-- Footer -->

        <div class="text-center mt-6 text-gray-600 text-sm">

            Built with ❤️ using Laravel 12 + HTMX + Tailwind CSS

        </div>

    </div>

    <!-- Toast Success Message -->

    <script>

        document.body.addEventListener('showMessage', function (evt) {

            let message = evt.detail.value;

            let toast = document.createElement('div');

            toast.innerHTML = `

                <div class="animate-fade fixed top-5 right-5 z-50 bg-green-500 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3">

                    <!-- Icon -->

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7" />

                    </svg>

                    <!-- Message -->

                    <span class="font-medium">

                        ${message}

                    </span>

                </div>

            `;

            document.getElementById('toast-container').appendChild(toast);

            setTimeout(() => {

                toast.remove();

            }, 3000);

        });

    </script>

</body>

</html>