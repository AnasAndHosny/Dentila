<?php

namespace App\Services\V1;

use App\Http\Resources\V1\PatientAccountResource;
use App\Http\Resources\V1\TransactionResource;
use App\Models\Patient;

class PatientAccountService
{
    public function transactions(Patient $patient): array
    {
        $patientAccount = $patient->account;
        $patientAccount = new PatientAccountResource($patientAccount);
        $message = __('messages.show_success', ['class' => __('patient transactions')]);
        $code = 200;
        return ['data' => $patientAccount, 'message' => $message, 'code' => $code];
    }

    public function myTransactions(): array
    {
        $patient = auth()->user()->patient;
        $patientAccount = $patient->account;
        $patientAccount = new PatientAccountResource($patientAccount);
        $message = __('messages.show_success', ['class' => __('patient transactions')]);
        $code = 200;
        return ['data' => $patientAccount, 'message' => $message, 'code' => $code];
    }

    public function deposit($request, Patient $patient): array
    {
        $note = 'عملية إيداع';
        if ($request['note']) $note .= ': ' . $request['note'];

        $transaction = $patient->account->applyTransaction(
            type: 'credit',
            amount: $request['amount'],
            treatmentId: null,
            note: $note,
            method: 'manual'
        );

        $transaction = new TransactionResource($transaction);

        $message = __('messages.store_success', ['class' => __('deposit')]);
        $code = 201;
        return ['data' =>  $transaction, 'message' => $message, 'code' => $code];
    }

    public function withdraw($request, Patient $patient): array
    {
        $note = 'عملية سحب';
        if ($request['note']) $note .= ': ' . $request['note'];

        $transaction = $patient->account->applyTransaction(
            type: 'debit',
            amount: $request['amount'],
            treatmentId: null,
            note: $note,
            method: 'manual'
        );

        $transaction = new TransactionResource($transaction);

        $message = __('messages.store_success', ['class' => __('withdraw')]);
        $code = 201;
        return ['data' =>  $transaction, 'message' => $message, 'code' => $code];
    }
}
