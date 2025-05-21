<?php

namespace App\Jobs;

use App\Models\System\Notification\UserNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\NotificationHelper;
use Carbon\Carbon;

class SendNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, NotificationHelper;

    public $tokens, $notification, $additionalData;

    /**
     * Create a new job instance.
     *
     * @param array $tokens the list of tokens to send the notification to
     * @param \App\Models\System\Notification\Notification $notification the notification to send
     * @param array $additionalData the additional data to send with the notification
     */
    public function __construct($tokens = [], $notification, $additionalData = [])
    {
        $this->tokens = $tokens;
        $this->notification = $notification;
        $this->additionalData = $additionalData;
    }

    public function handle(): void
    {
        $this->sendNotification(
            $this->tokens,
            $this->notification->title,
            $this->notification->body,
            $this->notification->page,
            $this->additionalData
        );
    }
}
