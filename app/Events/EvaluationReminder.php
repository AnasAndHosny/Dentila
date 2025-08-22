<?php

namespace App\Events;

use App\Models\TreatmentEvaluation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EvaluationReminder implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public TreatmentEvaluation $evaluation) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('User.' . $this->evaluation->patient->user->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'notification';
    }

    public function broadcastWith(): array
    {
        $planName = $this->evaluation->treatment?->name ?? 'خطة علاج';
        $date     = $this->evaluation->treatment?->created_at?->format('Y-m-d');

        return [
            'title_en' => 'Treatment Evaluation Reminder',
            'title_ar' => 'تذكير بتقييم خطة علاج',
            'body_en'  => "Please evaluate the treatment plan ($planName) performed on $date",
            'body_ar'  => "يرجى تقييم خطة العلاج ($planName) المجراة بتاريخ $date",
        ];
    }
}
