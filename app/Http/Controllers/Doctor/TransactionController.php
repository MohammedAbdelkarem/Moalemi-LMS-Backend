<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Services\Transaction\TransactionService;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionService $transactionService
    ) {}

    public function getMyTransactions(Request $request)
    {
        return success(
            $this->transactionService->getMyTransactions($request->all()),
            ApiMessages::MSG_SUCCESS,
            TransactionResource::class,
            $request->has('per_page')
        );
    }
}
