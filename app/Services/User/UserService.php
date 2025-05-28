<?php

namespace App\Services\User;

use App\Models\User;

/**
 * Class UserService.
 */
class UserService
{
    public function getPatients($data)
    {
        return getOrPaginate(
            User::where('role_id' , 4)->filter($data),
            $data
        );
    }
}
