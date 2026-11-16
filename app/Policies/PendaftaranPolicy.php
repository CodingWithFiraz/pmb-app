<?php

namespace App\Policies;

use App\Models\Pendaftaran;
use App\Models\User;

class PendaftaranPolicy
{
    /**
     * Pendaftar hanya boleh melihat/mengubah pendaftaran milik sendiri.
     * Admin boleh mengubah semua pendaftaran.
     */
    public function view(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->isAdmin() || $user->id === $pendaftaran->user_id;
    }

    public function update(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->isAdmin() || $user->id === $pendaftaran->user_id;
    }

    public function delete(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->isAdmin();
    }
}
