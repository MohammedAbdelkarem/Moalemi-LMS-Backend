<?php

namespace App\Http\Controllers\Administration\Transaction;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Services\Transaction\TransactionService;
use App\Http\Requests\Transaction\CreateCuponRequest;
use App\Http\Resources\Transaction\TransactionResource;
use App\Http\Requests\Transaction\CreateContextCuponRequest;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionService $transactionService
    ) {}

    public function get(Request $request)
    {
        return success(
            $this->transactionService->getAdminTransactions($request->all()),
            ApiMessages::MSG_SUCCESS,
            TransactionResource::class,
            $request->has('per_page')
        );
    }
    public function createStudentCupon(CreateCuponRequest $request)
    {
        $cupon = $this->transactionService->createStudentCupon($request->validated());
        
        return success(
            $cupon,
            ApiMessages::MSG_SUCCESS
        );
    }

    public function createContextCupon(CreateContextCuponRequest $request)
    {
        $cupon = $this->transactionService->createContextCupon($request->validated());
        
        return success(
            $cupon,
            ApiMessages::MSG_SUCCESS
        );
    }
}