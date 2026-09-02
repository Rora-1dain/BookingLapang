@php
    $warna = match ($tier) {
        'Platinum' => 'bg-gradient-to-r from-slate-300 to-slate-500 text-slate-900',
        'Gold' => 'bg-gradient-to-r from-yellow-300 to-yellow-500 text-yellow-900',
        default => 'bg-gradient-to-r from-gray-300 to-gray-400 text-gray-800',
    };
    $ikon = match ($tier) {
        'Platinum' => '💎',
        'Gold' => '🥇',
        default => '🥈',
    };
@endphp

<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold {{ $warna }}">
    {{ $ikon }} {{ $tier }}
</span>