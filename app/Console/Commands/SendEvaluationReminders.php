<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\EvaluationReminder;
use App\Models\TreatmentEvaluation;

class SendEvaluationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'evaluations:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for pending treatment evaluations that are due';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dueEvaluations = TreatmentEvaluation::due()->get();

        foreach ($dueEvaluations as $evaluation) {
            event(new EvaluationReminder($evaluation));
        }

        $this->info("Sent reminders for " . $dueEvaluations->count() . " evaluations.");
    }
}
