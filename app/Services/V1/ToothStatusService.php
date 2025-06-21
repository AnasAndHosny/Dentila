<?php

namespace App\Services\V1;

use App\Http\Resources\V1\ToothStatusResource;
use App\Models\ToothStatus;

class ToothStatusService
{
    public function index(): array
    {
        $toothStatuses = ToothStatus::all();
        $toothStatuses = ToothStatusResource::collection($toothStatuses);

        $message = __('messages.index_success', ['class' => __('tooth statuses')]);
        $code = 200;
        return ['data' => $toothStatuses, 'message' => $message, 'code' => $code];
    }
}
