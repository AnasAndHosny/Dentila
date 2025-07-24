<?php

namespace App\Listeners;

use App\Events\TreatmentCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateTeethStatus
{
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
        $patientTreatment = $event->patientTreatment;
        if ($patientTreatment['tooth_status_id'] != null) {
            $patientTreatment->patientTeeth()->update([
                'tooth_status_id' => $patientTreatment['tooth_status_id'],
            ]);
            
            $patientTreatment->update([
                'tooth_status_id' => null,
            ]);
        }
    }
}
