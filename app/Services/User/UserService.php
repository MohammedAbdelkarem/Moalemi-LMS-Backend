<?php

namespace App\Services\User;

use App\Models\Patient;
use App\Models\User;
use App\Traits\StorageHelper;

/**
 * Class UserService.
 */
class UserService
{
    use StorageHelper;
    public function getPatients($data)
    {
        return getOrPaginate(
            User::where('role_id' , 4)->with(['city', 'profile', 'archivedAccount'])->filter($data),
            $data
        );
    }

    public function updateOwnerInfo($data)
    {
        unset($data['avatar']);
        
        $user = auth()->user();
        $user->update($data + [
            'name' => $data['full_name'] ?? null
        ]);

        $patient=  Patient::where('is_owner' , 1)
                ->where('user_id' , auth()->id())
                ->first();

        if($patient)
            $patient->update($data);
        
        $user->save();
        $patient->save();
    }
}
