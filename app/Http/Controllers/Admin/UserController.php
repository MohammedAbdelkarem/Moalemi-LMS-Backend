<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Services\User\UserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\Profile\UserListResource;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}
    public function getPatients(Request $request)
    {
        return success(
            $this->userService->getPatients($request->all()),
            ApiMessages::MSG_SUCCESS,
            UserListResource::class,
            $request->has('per_page')
        );
    }
}
