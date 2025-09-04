<?php

namespace App\Services\Administration\Teacher;

use App\Constants\MediaCollection;
use App\Models\User;
use App\Services\MainService;
use App\Services\Administration\ResponsibilityService;

class TeacherService extends MainService
{
    public function __construct(
        protected ResponsibilityService $responsibilityService
    ) {}

    public function getAll($data)
    {
        return getOrPaginate(
            User::where('role_id', 3) // Teacher role
                ->with(['profile', 'city'])
                ->orderBy('created_at', 'desc'),
            $data
        );
    }

    public function show($id)
    {
        return User::where('role_id', 3)
            ->with(['profile', 'city', 'responsibilities.context'])
            ->findOrFail($id);
    }

    public function store($data)
    {
        // Create teacher user
        $teacher = User::create([
            'role_id' => 3, // Teacher role
            'name' => $data['name'],
            'phone_number' => $data['phone_number'],
            'bio' => $data['bio'],
            'email' => $data['email'] ?? null,
            'birth_date' => $data['birth_date'] ?? null,
            'is_male' => $data['is_male'] ?? null,
            'city_id' => $data['city_id'] ?? null,
        ]);

        if(isset($data['image']))
            uploadFileOnMedia($data['image'] , $teacher , MediaCollection::USER_COLLECTION);
    }

    public function getTeacherDetails($teacher_id)  
    {
        return User::findByIdOrFail($teacher_id);
    }
}
