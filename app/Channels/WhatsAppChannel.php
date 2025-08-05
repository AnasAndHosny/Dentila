<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class WhatsAppChannel
{
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toWhatsapp($notifiable);

        Http::withHeaders([
            'x-password' => config('services.whatsapp.password'),
        ])->post(config('services.whatsapp.url'), $data);
    }
}
