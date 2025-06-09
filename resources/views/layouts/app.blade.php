<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: false }" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link rel="icon" href="{{ asset('images/Skirknamecard.jpg') }}" type="image/png">

    <!-- Bootstrap & Tailwind -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')
    <style>
        body {
        font-family: Arial, Helvetica, sans-serif;
        min-height: 100vh;
        margin: 0;
        position: relative;
        background-color: #f8f9fa;
        overflow-y: auto;
    }

    body::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 50%;
        background-image: url('{{ asset('images/LockSceeen.png') }}');
        background-repeat: no-repeat;
        background-position: center top;
        background-size: cover;
        filter: blur(1px) brightness(50%);
        z-index: -1;

    }
    </style>
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

            {{-- @include('partials.footer') --}}
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('javascript/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


</body>

</html>

@stack('scripts')
