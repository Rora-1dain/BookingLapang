<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Booking Lapang') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
                display: inline-block;
                vertical-align: middle;
                line-height: 1;
            }
            .grass-shadow { box-shadow: 0 2px 6px -1px rgba(27, 46, 31, 0.06), 0 1px 3px -1px rgba(27, 46, 31, 0.04); }
            .grass-shadow-lg { box-shadow: 0 8px 16px -4px rgba(27, 46, 31, 0.08), 0 4px 6px -2px rgba(27, 46, 31, 0.04); }
            .shadow-warm-sm { box-shadow: 0 2px 6px -1px rgba(27, 46, 31, 0.05), 0 1px 3px -1px rgba(27, 46, 31, 0.03); }
            .shadow-warm-md { box-shadow: 0 8px 16px -4px rgba(27, 46, 31, 0.08), 0 4px 6px -2px rgba(27, 46, 31, 0.04); }
            .shadow-warm-lg { box-shadow: 0 14px 28px -6px rgba(27, 46, 31, 0.12), 0 6px 12px -3px rgba(27, 46, 31, 0.06); }
            .shadow-athletic-sm { box-shadow: 0 2px 6px -1px rgba(27, 46, 31, 0.05), 0 1px 3px -1px rgba(27, 46, 31, 0.03); }
            .shadow-athletic-md { box-shadow: 0 8px 16px -4px rgba(27, 46, 31, 0.08), 0 4px 6px -2px rgba(27, 46, 31, 0.04); }
        </style>
    </head>
    <body class="font-sans bg-background text-on-surface antialiased flex min-h-screen">
        {{ $slot }}
    </body>
</html>