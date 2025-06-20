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
        $user = auth()->user();
        $user->update($data + [
            'name' => $data['full_name'] ?? null
        ]);

        $patient=  Patient::where('is_owner' , 1)
                ->where('user_id' , auth()->id())
                ->first();

        $patient->update($data);

        if (isset($data["avatar"])) {
                $patient = $this->StoreUpdate(
                file: $data["avatar"],
                path: "patients/{$patient->id}",
                model: $patient,
                column: "avatar",
                deleteImage: true,
                singleFilePath: $patient->avatar ?? ""
            );

                $user = $this->StoreUpdate(
                file: $data["avatar"],
                path: "users/{$user->id}",
                model: $user,
                column: "avatar",
                deleteImage: true,
                singleFilePath: $user->avatar ?? ""
            );

            $user->save();
            $patient->save();
        }
    }
}
