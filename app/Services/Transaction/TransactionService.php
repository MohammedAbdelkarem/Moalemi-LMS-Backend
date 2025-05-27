<?php

namespace App\Services\Transaction;

use App\Models\Transaction;

/**
 * Class TransactionService.
 */
class TransactionService
{
    public function getMyTransactions($data)
    {
        return getOrPaginate(
            Transaction::doctorId()->with('subscription')->filter($data) ,
            $data
        );
    }
    public function getTransactions($data)
    {
        return getOrPaginate(
            Transaction::with(['subscription' , 'doctor'])->filter($data) ,
            $data
        );
    }

    

    public function generateTransaction($doctor_id , $subscription_id , $amount)
    {
        Transaction::create([
                'amount' => $amount,
                'doctor_id' => $doctor_id,
                'subscription_id' => $subscription_id
        ]);
    }
}
