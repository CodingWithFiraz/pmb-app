<?php

namespace App\Services;

use App\Models\Application;
use App\Models\ApplicationStatusHistory;
use App\Models\User;

class ApplicationService
{
    public function generateRegistrationNumber(Application $application): string
    {
        $year  = now()->year;
        $padded = str_pad($application->id, 6, '0', STR_PAD_LEFT);
        return "PMB-{$year}-{$padded}";
    }

    public function changeStatus(
        Application $application,
        string $toStatus,
        User $changedBy,
        ?string $note = null
    ): void {
        $fromStatus = $application->status;

        $application->update(['status' => $toStatus]);

        ApplicationStatusHistory::create([
            'application_id' => $application->id,
            'from_status'    => $fromStatus,
            'to_status'      => $toStatus,
            'changed_by'     => $changedBy->id,
            'note'           => $note,
            'created_at'     => now(),
        ]);
    }
}