<?php $__env->startSection('title','Pengaturan Notifikasi - Booking Lapang'); ?>
<?php $__env->startSection('content'); ?>
<main class="flex-grow w-full max-w-3xl mx-auto px-6 md:px-12 py-8">
    <h1 class="text-headline-lg font-headline-lg text-on-surface tracking-tight">Pengaturan Notifikasi</h1>
    <p class="text-body-md font-body-md text-on-surface-variant mt-1 mb-6">
        Pilih lewat mana kamu mau menerima tiap jenis notifikasi. Kalau semuanya dimatikan, notifikasi tetap
        masuk in-app. Notifikasi penting (hasil verifikasi, ulasan buruk, refund gagal) selalu dikirim lewat email
        dan tidak bisa dimatikan.
    </p>

    <?php if(session('success')): ?>
        <p class="mb-4 px-4 py-3 rounded-lg bg-primary-fixed text-on-primary-fixed text-sm font-semibold"><?php echo e(session('success')); ?></p>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <p class="mb-4 px-4 py-3 rounded-lg bg-error-container text-on-error-container text-sm font-semibold">Preferensi gagal disimpan. Coba lagi.</p>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('notifikasi.preferensi.update')); ?>" class="tactile-card rounded-2xl p-5 tactile-shadow">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="grid grid-cols-[1fr_auto_auto] gap-x-6 gap-y-4 items-center">
            <span class="text-label-sm font-label-sm text-on-surface-variant uppercase">Jenis</span>
            <span class="text-label-sm font-label-sm text-on-surface-variant uppercase text-center">Email</span>
            <span class="text-label-sm font-label-sm text-on-surface-variant uppercase text-center">In-app</span>

            <?php $__currentLoopData = $tipe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kunci => [$label, $deskripsi]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div>
                    <p class="font-semibold text-on-surface"><?php echo e($label); ?></p>
                    <p class="text-body-sm text-on-surface-variant"><?php echo e($deskripsi); ?></p>
                </div>
                <?php $__currentLoopData = ['email', 'database']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $channel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="text-center">
                        
                        <input type="hidden" name="preferensi[<?php echo e($kunci); ?>][<?php echo e($channel); ?>]" value="0">
                        <input type="checkbox"
                               name="preferensi[<?php echo e($kunci); ?>][<?php echo e($channel); ?>]"
                               value="1"
                               class="w-5 h-5 rounded"
                               <?php if($preferensi[$kunci][$channel]): echo 'checked'; endif; ?>>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <button type="submit" class="mt-6 bg-primary text-on-primary font-bold px-5 py-2.5 rounded-xl">Simpan</button>
    </form>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\Booking\BookingLapang\booking-lapang\resources\views/pengaturan/notifikasi.blade.php ENDPATH**/ ?>