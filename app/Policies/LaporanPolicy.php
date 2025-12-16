<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LaporanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if user can view any laporan
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view
    }

    /**
     * Determine if user can view laporan
     */
    public function view(User $user): bool
    {
        return true; // All authenticated users can view
    }

    /**
     * Determine if user can create laporan
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'superadmin']);
    }

    /**
     * Determine if user can update laporan
     */
    public function update(User $user): bool
    {
        return in_array($user->role, ['admin', 'superadmin']);
    }

    /**
     * Determine if user can delete laporan
     */
    public function delete(User $user): bool
    {
        return in_array($user->role, ['admin', 'superadmin']);
    }

    /**
     * Determine if user can download laporan
     */
    public function download(User $user): bool
    {
        return true; // All authenticated users can download
    }
}