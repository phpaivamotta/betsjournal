<?php

namespace App\Listeners;

use App\Services\EmailValueBetsService;

class SendValueBetsEmail
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $subscriber = $event->subscriber;

        (new EmailValueBetsService)->sendValueBetsEmail($subscriber);
    }
}
