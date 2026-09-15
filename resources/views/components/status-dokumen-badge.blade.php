@props(['status'])

@php
    $styles = match($status) {
        'Valid' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
        'Ditolak' => 'bg-red-500/10 text-red-400 border-red-500/20',
        default => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-block px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider border $styles"]) }}>
    {{ $status ?? 'Menunggu Validasi' }}
</span>
