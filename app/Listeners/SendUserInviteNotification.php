<?php

namespace App\Listeners;

use App\Events\UserInvitedEvent;
use App\Notifications\UserInviteNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendUserInviteNotification
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
    public function handle(UserInvitedEvent $event): void
    {
        $event->invite->notify(new UserInviteNotification($event->invite));
    }
}
