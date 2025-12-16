@props(['type' => 'info', 'message'])

@php
    $classes = [
        'success' => 'alert-success',
        'error' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info',
    ];

    $icons = [
        'success' => 'check-circle',
        'error' => 'exclamation-circle',
        'warning' => 'exclamation-triangle',
        'info' => 'info-circle',
    ];
@endphp

<div class="alert {{ $classes[$type] }} alert-dismissible fade show" role="alert">
    <i class="fas fa-{{ $icons[$type] }} me-2"></i>
    {{ $message ?? $slot }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>