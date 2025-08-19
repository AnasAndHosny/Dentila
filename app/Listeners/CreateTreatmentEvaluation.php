<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Events\TreatmentCompleted;
use App\Models\TreatmentEvaluation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateTreatmentEvaluation implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TreatmentCompleted $event): void
    {
        $treatment = $event->patientTreatment;

        // حماية من التكرار
        TreatmentEvaluation::firstOrCreate(
            ['patient_treatment_id' => $treatment->id],
            [
                'patient_id'   => $treatment->patient_id,
                'doctor_id'    => $treatment->doctor_id,            // تأكد أن doctor_id موجود بالخطة
            ]
        );
    }
}
