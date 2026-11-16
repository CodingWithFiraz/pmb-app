<?php

namespace App\Notifications;

use App\Models\Pendaftaran;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengumumanDikeluarkan extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Pendaftaran $pendaftaran,
        public string $hasilKelulusan,
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'pendaftaran_id' => $this->pendaftaran->id,
            'nomor_pendaftaran' => $this->pendaftaran->nomor_pendaftaran,
            'hasil_kelulusan' => $this->hasilKelulusan,
            'message' => 'Hasil pengumuman kelulusan pendaftaran '.$this->pendaftaran->nomor_pendaftaran.' telah dikeluarkan.',
            'url' => route('pendaftar.pengumuman'),
        ];
    }
}
