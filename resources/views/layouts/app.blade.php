<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://preline.co/assets/css/main.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@loadingio/loading-bar@0.1.1/dist/loading-bar.min.css">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased dark:bg-neutral-900">

    @include('layouts.navigation')



    <!-- Page Content -->
    <main class="relative h-screen w-full px-6 py-4">
        {{ $slot }}
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.js"
    integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin=" "></script>
    <script src="https://cdn.jsdelivr.net/npm/preline/dist/index.js"></script>
    <script src="https://preline.co/assets/vendor/prism/prism.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    @yield('script')
</body>

</html>
