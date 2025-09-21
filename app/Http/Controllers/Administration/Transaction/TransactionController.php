<?php

namespace App\Http\Controllers\Administration\Transaction;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Services\Transaction\TransactionService;
use App\Http\Requests\Transaction\CreateCuponRequest;
use App\Http\Resources\Transaction\TransactionResource;
use App\Http\Requests\Transaction\CreateContextCuponRequest;
use App\Http\Resources\Copon\CopnoResource;
use App\Services\Copon\CoponService;

class TransactionController extends Controller
{
    public function __construct(
        protected CoponService $coponService,
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
        $this->coponService->createStudentCupon($request->validated());
        
        return success(
            [],
            ApiMessages::MSG_SUCCESS
        );
    }

    public function createContextCupon(CreateContextCuponRequest $request)
    {
        $cupon = $this->coponService->createContextCupon($request->validated());
        
        return success(
            $cupon,
            ApiMessages::MSG_SUCCESS
        );
    }

    public function setCoponsAsExpired(Request $request)
    {   
        return success(
            $this->coponService->setAsExpired($request->only('ids')),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function getCopons(Request $request)
    {
        return success(
            $this->coponService->get($request->all()),
            ApiMessages::MSG_SUCCESS,
            CopnoResource::class,
            $request->has('per_page')
        );
    }
}