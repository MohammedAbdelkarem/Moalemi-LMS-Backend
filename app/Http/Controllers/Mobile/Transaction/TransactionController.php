<?php

namespace App\Http\Controllers\Mobile\Transaction;

use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Services\Transaction\TransactionService;
use App\Http\Requests\Transaction\UseCuponRequest;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionService $transactionService
    ) {}

    public function useStudentCupon(UseCuponRequest $request)
    {
        $transaction = $this->transactionService->useStudentCupon($request->cupon);
        
        return success(
            $transaction,
            ApiMessages::MSG_SUCCESS
        );
    }

    public function useContextCupon(UseCuponRequest $request)
    {
        $transaction = $this->transactionService->useContextCupon($request->cupon);
        
        return success(
            $transaction,
            ApiMessages::MSG_SUCCESS
        );
    }

    public function directPurchase(Request $request)
    {
        $request->validate([
            'context_type' => 'required|string',
            'context_id' => 'required|integer'
        ]);

        $transaction = $this->transactionService->directPurchase($request->all());
        
        return success(
            $transaction,
            ApiMessages::MSG_SUCCESS
        );
    }
}