<?php

if (!function_exists('formatFileSize')) {
    /**
     * Format file size to human readable
     */
    function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}

if (!function_exists('formatTanggalIndonesia')) {
    /**
     * Format tanggal ke Bahasa Indonesia
     */
    function formatTanggalIndonesia($date, $format = 'long')
    {
        $bulan = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $timestamp = strtotime($date);
        $hari = date('d', $timestamp);
        $bulanAngka = date('n', $timestamp);
        $tahun = date('Y', $timestamp);

        if ($format === 'short') {
            return $hari . ' ' . substr($bulan[$bulanAngka], 0, 3) . ' ' . $tahun;
        }

        return $hari . ' ' . $bulan[$bulanAngka] . ' ' . $tahun;
    }
}

if (!function_exists('getRoleBadgeClass')) {
    /**
     * Get Bootstrap badge class based on role
     */
    function getRoleBadgeClass($role)
    {
        $classes = [
            'superadmin' => 'bg-danger',
            'admin' => 'bg-warning',
            'user' => 'bg-secondary',
        ];

        return $classes[$role] ?? 'bg-secondary';
    }
}

if (!function_exists('getRoleLabel')) {
    /**
     * Get role label in Indonesian
     */
    function getRoleLabel($role)
    {
        $labels = [
            'superadmin' => 'Super Admin',
            'admin' => 'Admin',
            'user' => 'User',
        ];

        return $labels[$role] ?? 'Unknown';
    }
}

if (!function_exists('canManage')) {
    /**
     * Check if current user can manage (admin or superadmin)
     */
    function canManage()
    {
        return auth()->check() && auth()->user()->canManage();
    }
}

if (!function_exists('isSuperAdmin')) {
    /**
     * Check if current user is superadmin
     */
    function isSuperAdmin()
    {
        return auth()->check() && auth()->user()->isSuperAdmin();
    }
}

if (!function_exists('generatePeriodeMinggu')) {
    /**
     * Generate periode minggu string
     */
    function generatePeriodeMinggu($tanggal)
    {
        $timestamp = strtotime($tanggal);
        $mingguKe = ceil(date('j', $timestamp) / 7);
        $bulan = formatTanggalIndonesia($tanggal, 'short');

        return "Minggu ke-{$mingguKe} " . date('F Y', $timestamp);
    }
}

if (!function_exists('getBulanIndonesia')) {
    /**
     * Get Indonesian month name
     */
    function getBulanIndonesia($bulanAngka)
    {
        $bulan = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        return $bulan[$bulanAngka] ?? '';
    }
}

if (!function_exists('getStatisticColor')) {
    /**
     * Get color for statistics card
     */
    function getStatisticColor($index)
    {
        $colors = [
            ['bg' => '#e3f2fd', 'text' => '#1976d2'],
            ['bg' => '#f3e5f5', 'text' => '#7b1fa2'],
            ['bg' => '#e8f5e9', 'text' => '#388e3c'],
            ['bg' => '#fff3e0', 'text' => '#f57c00'],
            ['bg' => '#ffebee', 'text' => '#d32f2f'],
        ];

        return $colors[$index % count($colors)];
    }
}