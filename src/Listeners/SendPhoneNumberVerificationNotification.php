<?php

namespace AlhajiAki\PhoneNumberVerification\Listeners;

use AlhajiAki\PhoneNumberVerification\Contracts\MustVerifyPhoneNumber;
use Illuminate\Auth\Events\Registered;

class SendPhoneNumberVerificationNotification
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if ($event->user instanceof MustVerifyPhoneNumber && !$event->user->hasVerifiedPhoneNumber()) {
            $event->user->generatePhoneNumberVerificationToken();
        }
    }
}
