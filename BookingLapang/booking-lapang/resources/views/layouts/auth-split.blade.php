<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Booking Lapang')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 500, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
            line-height: 1;
        }
        .grass-shadow { box-shadow: 0 14px 28px -6px rgba(27, 46, 31, 0.12), 0 6px 12px -3px rgba(27, 46, 31, 0.06); }
        .resting-shadow { box-shadow: 0 2px 6px -1px rgba(27, 46, 31, 0.05), 0 1px 3px -1px rgba(27, 46, 31, 0.03); }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col selection:bg-primary selection:text-white">
    @yield('content')
    @stack('scripts')
</body>
</html>
