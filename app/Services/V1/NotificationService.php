<?php

namespace App\Services\V1;

use Illuminate\Support\Facades\Auth;

class NotificationService
{

    public function index(): array
    {
        $notifications = Auth::user()->unreadNotifications;

        $data = [];
        foreach ($notifications as $notification) {
            $data[] = [
                'title_en' => $notification->data['title_en'],
                'title_ar' => $notification->data['title_ar'],
                'body_en' => $notification->data['body_en'],
                'body_ar' => $notification->data['body_ar'],
            ];
        }

        $message = __('messages.index_success', ['class' => __('notifications')]);
        $code = 200;
        return ['data' => $data, 'message' => $message, 'code' => $code];
    }

    public function markAllAsRead(): array
    {
        Auth::user()->unreadNotifications->markAsRead();

        $message = __('messages.update_success', ['class' => __('notifications')]);
        $code = 200;
        return ['data' => [], 'message' => $message, 'code' => $code];
    }
}
