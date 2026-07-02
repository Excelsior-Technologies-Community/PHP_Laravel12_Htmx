<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel 12 HTMX Task Manager</title>

    <script src="https://unpkg.com/htmx.org@1.9.10"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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

        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            font-size: 14px;
            gap: 6px;
        }

        .btn-icon:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn-edit {
            background: #f59e0b;
            color: white;
        }

        .btn-edit:hover {
            background: #d97706;
        }

        .btn-delete {
            background: #ef4444;
            color: white;
        }

        .btn-delete:hover {
            background: #dc2626;
        }

        .btn-add {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .btn-add:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
        }

        .task-card {
            transition: all 0.3s ease;
        }

        .task-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .priority-high {
            background: #fee2e2;
            color: #dc2626;
        }

        .priority-medium {
            background: #fef3c7;
            color: #d97706;
        }

        .priority-low {
            background: #d1fae5;
            color: #059669;
        }

        .status-completed {
            background: #d1fae5;
            color: #059669;
        }

        .status-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status-overdue {
            background: #fee2e2;
            color: #dc2626;
        }

        .modal-overlay {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
        }

        .modal-box {
            animation: modalIn 0.3s ease;
        }

        @keyframes modalIn {
            from {
                transform: scale(0.9) translateY(20px);
                opacity: 0;
            }
            to {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-indigo-100 via-sky-50 to-cyan-100 flex items-center justify-center p-5 overflow-x-hidden">

    <div class="absolute top-0 left-0 w-72 h-72 bg-purple-300 rounded-full blur-3xl opacity-30"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-cyan-300 rounded-full blur-3xl opacity-30"></div>

    <div id="toast-container"></div>

    <div class="relative z-10 w-full max-w-5xl">

        <div class="glass shadow-2xl rounded-[35px] overflow-hidden border border-white/40">

            <div class="bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-500 px-8 py-10 text-center text-white relative overflow-hidden">
                <div class="absolute top-0 left-0 w-44 h-44 bg-white/10 rounded-full -translate-x-20 -translate-y-20"></div>
                <div class="absolute bottom-0 right-0 w-60 h-60 bg-white/10 rounded-full translate-x-20 translate-y-20"></div>

                <h1 class="text-4xl md:text-5xl font-bold tracking-wide relative z-10">
                    <i class="fas fa-tasks mr-3"></i>Task Manager
                </h1>
                <p class="mt-4 text-blue-100 text-lg relative z-10">
                    Manage tasks instantly without page reloads ⚡
                </p>
            </div>

            <div class="p-8 bg-white/70">
                @if($errors->any())
                    <div class="animate-fade mb-6 bg-red-100 border border-red-300 text-red-700 px-5 py-4 rounded-2xl shadow-md">
                        <div class="font-semibold mb-2">Please fix the following errors:</div>
                        <ul class="space-y-1 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>

        </div>

        <div class="text-center mt-6 text-gray-600 text-sm">
            Built with ❤️ using Laravel 12 + HTMX + Tailwind CSS
        </div>
    </div>

    <script>
        document.body.addEventListener('showMessage', function (evt) {
            let message = evt.detail.value;
            let toast = document.createElement('div');
            toast.innerHTML = `
                <div class="animate-fade fixed top-5 right-5 z-50 bg-green-500 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3">
                    <i class="fas fa-check-circle text-xl"></i>
                    <span class="font-medium">${message}</span>
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