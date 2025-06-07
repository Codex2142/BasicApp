<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Your custom styles -->
    <style>
        .bg-amber-600 {
            background-color: #d97706;
        }
        .hover\:bg-amber-700:hover {
            background-color: #b45309;
        }
        .text-amber-600 {
            color: #d97706;
        }
        .hover\:text-amber-500:hover {
            color: #f59e0b;
        }
        .focus\:ring-amber-500:focus {
            ring-color: #f59e0b;
        }
        .focus\:border-amber-500:focus {
            border-color: #f59e0b;
        }
    </style>

</head>
<body class="font-sans antialiased">
    @yield('content')

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
