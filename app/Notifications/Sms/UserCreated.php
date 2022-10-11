<?php

namespace App\Notifications\Sms;

use App\Models\User;
use App\Channels\SmsChannel;
use App\Models\Sms;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [SmsChannel::class];
    }

    public function toSms($notifiable)
    {
        return [
            'pattern_code' => option('user_register_pattern_code'),
            'mobile'       => $notifiable->username,
            'input_data'   => [
                'fullname' => $notifiable->fullname
            ],
            'type'    => Sms::TYPES['USER_CREATED']['key'],
            'user_id' => $notifiable->id
        ];
    }
}
