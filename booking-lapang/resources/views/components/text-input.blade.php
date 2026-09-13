@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full h-12 px-4 bg-surface-container-lowest border border-surface-dim rounded-xl font-body-md text-body-md text-on-surface placeholder:text-outline/60 focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all duration-150']) }}>