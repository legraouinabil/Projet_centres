{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
@vite('resources/css/app.css')
@vite('resources/js/app.js')

    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Configure Tailwind for the "Inter" font */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');

        :root {
            font-family: 'Inter', sans-serif;
        }

        /* Custom styles for the subtle background texture */
        .textured-bg {
            background-color: #f5f5f5;
            /* Light gray base */
            background-image: url("data:image/svg+xml,%3Csvg width='6' height='6' viewBox='0 0 6 6' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23e0e0e0' fill-opacity='0.4'%3E%3Cpath d='M3 0h3v3H3V0zM0 3h3v3H0V3z'/%3E%3C/g%3E%3C/svg%3E");
        }

        /* Custom dropdown positioning for better control */
        .dropdown-menu {
            transition: all 0.3s ease-in-out;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
        }

        .nav-item:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
    </style>
    @livewireStyles
</head>

<body class="bg-gray-50">
    <div id="app">
        <!-- Navigation -->
        @include('layout.navigation')
        <!-- Header Section -->

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @livewireScripts
</body>

</html>