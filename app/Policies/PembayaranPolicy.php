<?php

namespace App\Policies;

use App\Models\Pembayaran;
use App\Models\User;

class PembayaranPolicy
{
    /**
     * Pendaftar hanya boleh melihat/mengubah pembayaran milik pendaftarannya sendiri.
     * Admin boleh mengubah semua pembayaran.
     */
    public function view(User $user, Pembayaran $pembayaran): bool
    {
        return $user->isAdmin() || $user->id === $pembayaran->pendaftaran->user_id;
    }

    public function update(User $user, Pembayaran $pembayaran): bool
    {
        return $user->isAdmin() || $user->id === $pembayaran->pendaftaran->user_id;
    }

    public function delete(User $user, Pembayaran $pembayaran): bool
    {
        return $user->isAdmin();
    }
}
