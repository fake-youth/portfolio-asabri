@props(['icon', 'value', 'label', 'color' => 'blue', 'link' => null])

@php
    $colors = [
        'blue' => ['bg' => '#e3f2fd', 'text' => '#1976d2'],
        'purple' => ['bg' => '#f3e5f5', 'text' => '#7b1fa2'],
        'green' => ['bg' => '#e8f5e9', 'text' => '#388e3c'],
        'orange' => ['bg' => '#fff3e0', 'text' => '#f57c00'],
        'red' => ['bg' => '#ffebee', 'text' => '#d32f2f'],
    ];
@endphp

<div class="stat-card">
    <div class="icon" style="background: {{ $colors[$color]['bg'] }}; color: {{ $colors[$color]['text'] }};">
        <i class="fas fa-{{ $icon }}"></i>
    </div>
    <h3>{{ $value }}</h3>
    <p class="mb-3">{{ $label }}</p>

    @if($link)
        <a href="{{ $link }}" class="btn btn-asabri btn-sm">
            <i class="fas fa-eye"></i> Lihat Detail
        </a>
    @endif

    {{ $slot }}
</div>