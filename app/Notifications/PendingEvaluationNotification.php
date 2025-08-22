<?php

namespace App\Notifications;

use Illuminate\Support\Arr;
use Illuminate\Bus\Queueable;
use App\Models\TreatmentEvaluation;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class PendingEvaluationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public TreatmentEvaluation $evaluation) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $planName = $this->evaluation->treatment?->name ?? 'خطة علاج';
        $date     = $this->evaluation->treatment?->created_at?->format('Y-m-d');

        return [
            'title_ar' => 'تقييم خطة علاج',
            'title_en' => 'Treatment Evaluation',
            'body_ar'  => "يرجى تقييم خطة العلاج ($planName) المجراة بتاريخ $date",
            'body_en'  => "Please evaluate the treatment plan ($planName) performed on $date",
        ];
    }
}
