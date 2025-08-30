<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\QueueTurn;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\QueueTurnResource;
use App\Repositories\V1\QueueTurnRepository;
use App\Http\Requests\V1\QueueTurn\StoreQueueTurnRequest;
use App\Http\Requests\V1\QueueTurn\UpdateQueueTurnRequest;

class QueueTurnController extends Controller
{
    protected $queueTurnRepository;

    public function __construct(QueueTurnRepository $queueTurnRepository)
    {
        $this->queueTurnRepository = $queueTurnRepository;
    }


    public function store(StoreQueueTurnRequest $request)
    {
        $queueTurn = $this->queueTurnRepository->store($request->validated());

        return response()->json([
            'status'  => 1,
            'data'    => [
                'id'           => $queueTurn->id,
                'patient_id'   => $queueTurn->patient_id,
                'doctor_id'    => $queueTurn->employee_id,
                'status'       => $queueTurn->status->name,
                'arrival_time' => $queueTurn->arrival_time,
            ],
            'message' => __('messages.queue_turn.created_successfully'),
        ]);
    }


    public function update(UpdateQueueTurnRequest $request, QueueTurn $queueTurn)
    {
        try {
            $queueTurn = $this->queueTurnRepository->update($request, $queueTurn);

            return response()->json([
                'status'  => 1,
                'data'    => [
                    'id'           => $queueTurn->id,
                    'patient_id'   => $queueTurn->patient_id,
                    'doctor_id'    => $queueTurn->employee_id,
                    'status'       => $queueTurn->status->name,
                    'arrival_time' => $queueTurn->arrival_time,
                ],
                'message' => __('messages.queue_turn.updated_successfully'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage(), // الرسالة اللي رجعناها من الريبو
            ], 422);
        }
    }

    public function index()
    {
        $queueTurns = $this->queueTurnRepository->getQueueTurns();

        $queueTurns = QueueTurnResource::collection($queueTurns);

        $message = __('messages.queue_turn.list');
        $code = 200;
        return ApiResponse::Success($queueTurns, $message, $code);
    }


    public function history()
    {
        $queueTurns = $this->queueTurnRepository->getHistory();

        return response()->json([
            'status'  => 1,
            'data'    => QueueTurnResource::collection($queueTurns),
            'message' => __('messages.queue_turn.list'),
        ]);
    }
}
