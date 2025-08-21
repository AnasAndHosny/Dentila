<?php

namespace App\Console\Commands;

use App\Models\PatientAccount;
use App\Events\PaymentReminder;
use Illuminate\Console\Command;

class SendPaymentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patients:payment-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send payment reminders to patients with negative balance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("🔔 Sending payment reminders...");

        PatientAccount::where('balance', '<', 0)
            ->with('patient.user')
            ->get()
            ->each(function ($account) {
                if ($account->patient?->user) {
                    event(new PaymentReminder($account));
                    $this->info("Reminder event dispatched for: {$account->patient->user->name}");
                }
            });

        return Command::SUCCESS;
    }
}
