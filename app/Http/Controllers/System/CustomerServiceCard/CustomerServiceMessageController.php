<?php

namespace App\Http\Controllers\System\CustomerServiceCard;

use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\System\CustomerServiceCard\CustomerServiceMessageRequest;
use App\Http\Resources\System\CustomerServiceCard\CustomerServiceMessageResource;
use App\Models\System\CustomerService\CustomerServiceCard;
use App\Services\System\CustomerServiceCard\CustomerServiceMessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerServiceMessageController extends Controller
{
    public function __construct(
        protected CustomerServiceMessageService $messageService
    ) {}

    public function index(Request $request): JsonResponse
    {
        return success(
            $this->messageService->index($request->per_page, $request->card_id),
            ApiMessages::MSG_SUCCESS,
            CustomerServiceMessageResource::class,
            true
        );
    }

    public function store(CustomerServiceMessageRequest $request): JsonResponse
    {
        return createdSuccess(
            $this->messageService->store($request->validated()),
            ApiMessages::MSG_CREATED
        );
    }

    public function destroy(string $id): JsonResponse
    {
        return success(
            $this->messageService->destroy($id),
            ApiMessages::MSG_DELETED,
        );
    }
}
