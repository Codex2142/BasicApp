<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: false }" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <!-- Bootstrap & Tailwind -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.png" type="image/png">
</head>

<body class="h-100 bg-gray-100">

    <!-- Navbar -->
    @include('partials.navbar')

    <div class="container-fluid h-100">
        <div class="row h-100">
            <!-- Sidebar -->
            <div class="mt-5">
                @include('partials.sidebar')
            </div>

            <!-- Main Content -->
            <main class="col-md-10 ms-auto px-4 py-4 overflow-auto -mt-5">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>

@stack('scripts')
