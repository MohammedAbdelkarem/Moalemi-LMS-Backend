<?php

namespace App\Services;

use App\Models\NotificationManagement;

/**
 * Class PatientNotificationService.
 */
class PatientNotificationService
{
    public function getSettings()
    {
        return auth()->user()->notification_management;
    }

    public function update($id , $type)
    {
        $setting = NotificationManagement::find($id);

        $column = $type . '_notification';

        $setting->$column = ($setting->$column == 1) ? 0 : 1;

        $setting->save();
    }
}
