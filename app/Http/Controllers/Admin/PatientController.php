<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Resources\Patient\PatientResource;
use App\Services\Patient\PatientService;

class PatientController extends Controller
{
    public function __construct(
        protected PatientService $patientService,
    ) {}

    public function index($user_id)
    {
        return success(
            $this->patientService->getMyRelations( $user_id),
            ApiMessages::MSG_SUCCESS,
            PatientResource::class,
        );
    }
}
