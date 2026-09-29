@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'flex items-center gap-2 font-body-md text-body-md text-on-primary-fixed-variant bg-primary/10 border border-primary/30 rounded-xl px-4 py-3']) }}>
        <span class="material-symbols-outlined text-primary text-[18px]">check_circle</span>
        <span>{{ $status }}</span>
    </div>
@endif