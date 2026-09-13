@props(['type' => 'submit'])

<button {{ $attributes->merge(['type' => $type, 'class' => 'w-full h-12 flex items-center justify-center gap-2 rounded-xl bg-primary hover:bg-primary-container text-on-primary font-label-lg text-label-lg font-bold shadow-grass-resting active:scale-[0.98] transition-all duration-150']) }}>
    {{ $slot }}
</button>
