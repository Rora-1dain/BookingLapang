<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['status']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['status']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if($status): ?>
    <div <?php echo e($attributes->merge(['class' => 'flex items-center gap-2 font-body-md text-body-md text-on-primary-fixed-variant bg-primary/10 border border-primary/30 rounded-xl px-4 py-3'])); ?>>
        <span class="material-symbols-outlined text-primary text-[18px]">check_circle</span>
        <span><?php echo e($status); ?></span>
    </div>
<?php endif; ?><?php /**PATH D:\laragon\www\Booking\booking-lapang\resources\views/components/auth-session-status.blade.php ENDPATH**/ ?>