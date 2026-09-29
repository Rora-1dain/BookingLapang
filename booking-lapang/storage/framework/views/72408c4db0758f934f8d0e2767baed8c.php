<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Booking Lapang')); ?></title>

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
    </style>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-background text-on-surface font-body-md antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center px-6 py-10">
        <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2.5 mb-8">
            <span class="w-11 h-11 rounded-xl bg-primary-container flex items-center justify-center text-on-primary">
                <span class="material-symbols-outlined text-[22px]">sports_soccer</span>
            </span>
            <span class="flex flex-col leading-none">
                <span class="font-title-lg text-title-lg text-primary font-bold">Booking Lapang</span>
                <span class="font-body-sm text-body-sm text-on-surface-variant">Main. Booking. Beres.</span>
            </span>
        </a>

        <div class="w-full sm:max-w-md bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-grass-floating p-6 sm:p-8">
            <?php echo e($slot); ?>

        </div>

        <a href="<?php echo e(route('home')); ?>" class="mt-6 inline-flex items-center gap-1.5 font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            <span>Kembali ke Beranda</span>
        </a>
    </div>
</body>
</html><?php /**PATH D:\laragon\www\Booking\booking-lapang\resources\views/layouts/guest.blade.php ENDPATH**/ ?>