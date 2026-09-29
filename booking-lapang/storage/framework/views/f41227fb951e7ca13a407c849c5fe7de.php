<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['type' => 'submit']));

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

foreach (array_filter((['type' => 'submit']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<button <?php echo e($attributes->merge(['type' => $type, 'class' => 'w-full h-12 flex items-center justify-center gap-2 rounded-xl bg-primary hover:bg-primary-container text-on-primary font-label-lg text-label-lg font-bold shadow-grass-resting active:scale-[0.98] transition-all duration-150'])); ?>>
    <?php echo e($slot); ?>

</button>
<?php /**PATH D:\laragon\www\Booking\booking-lapang\resources\views/components/primary-button.blade.php ENDPATH**/ ?>