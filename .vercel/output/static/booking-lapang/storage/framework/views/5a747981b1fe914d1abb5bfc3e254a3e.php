<?php $__env->startSection('content'); ?>
<div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

    <h2 class="text-2xl font-bold text-gray-800 mb-4">Dashboard Admin</h2>

    <form method="GET" action="<?php echo e(route('admin.dashboard')); ?>" class="flex items-end gap-4 mb-6 bg-white p-4 rounded-lg shadow">
        <div>
            <label class="block text-sm text-gray-600 mb-1">Dari</label>
            <input type="date" name="dari" value="<?php echo e($dari); ?>" class="border-gray-300 rounded-md shadow-sm text-sm">
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Sampai</label>
            <input type="date" name="sampai" value="<?php echo e($sampai); ?>" class="border-gray-300 rounded-md shadow-sm text-sm">
        </div>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-md">
            Filter
        </button>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Total Pendapatan</p>
            <p class="text-xl font-bold text-gray-800">Rp<?php echo e(number_format($totalPendapatan)); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Total Booking</p>
            <p class="text-xl font-bold text-gray-800"><?php echo e(array_sum($bookingPerStatus)); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Lapangan Terfavorit</p>
            <p class="text-xl font-bold text-gray-800"><?php echo e($lapanganFavorit[0]['nama_lapangan'] ?? '-'); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Tingkat Pembatalan</p>
            <p class="text-xl font-bold text-gray-800"><?php echo e($tingkatPembatalan); ?>%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-sm font-semibold text-gray-600 mb-2">Pendapatan per Bulan</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="chartPendapatan"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-sm font-semibold text-gray-600 mb-2">Booking per Status</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="chartStatus"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4 mb-6 overflow-x-auto">
        <h3 class="text-sm font-semibold text-gray-600 mb-3">5 Booking Terbaru</h3>
        <table class="min-w-full text-sm text-left">
            <thead class="text-gray-500 border-b">
                <tr>
                    <th class="py-2 pr-4">User</th>
                    <th class="py-2 pr-4">Lapangan</th>
                    <th class="py-2 pr-4">Tanggal</th>
                    <th class="py-2 pr-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $bookingTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="py-2 pr-4"><?php echo e($b->user->name ?? '-'); ?></td>
                        <td class="py-2 pr-4"><?php echo e($b->lapangan->nama_lapangan ?? '-'); ?></td>
                        <td class="py-2 pr-4"><?php echo e($b->tanggal_booking); ?></td>
                        <td class="py-2 pr-4"><?php echo e($b->status); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4" class="py-3 text-gray-400">Belum ada booking.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

        <div class="bg-white rounded-lg shadow p-4 overflow-x-auto">
        <h3 class="text-sm font-semibold text-gray-600 mb-3">5 User Paling Aktif</h3>
        <table class="min-w-full text-sm text-left">
            <thead class="text-gray-500 border-b">
                <tr>
                    <th class="py-2 pr-4">Nama</th>
                    <th class="py-2 pr-4">Jumlah Booking</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__currentLoopData = $userAktif; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="py-2 pr-4"><?php echo e($item['nama'] ?? '-'); ?></td>
                        <td class="py-2 pr-4"><?php echo e($item['total_booking']); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('chartPendapatan'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_keys($pendapatanBulanan), 15, 512) ?>,
        datasets: [{
            label: 'Pendapatan per Bulan',
            data: <?php echo json_encode(array_values($pendapatanBulanan), 15, 512) ?>,
            backgroundColor: '#4e73df'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

new Chart(document.getElementById('chartStatus'), {
    type: 'pie',
    data: {
        labels: <?php echo json_encode(array_keys($bookingPerStatus), 15, 512) ?>,
        datasets: [{
            data: <?php echo json_encode(array_values($bookingPerStatus), 15, 512) ?>,
            backgroundColor: ['#f6c23e', '#1cc88a', '#e74a3b']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\Booking\booking-lapang\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>