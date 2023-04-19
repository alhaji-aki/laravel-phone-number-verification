<?php

namespace AlhajiAki\PhoneNumberVerification\Listeners;

use AlhajiAki\PhoneNumberVerification\Contracts\MustVerifyPhoneNumber;
use Illuminate\Auth\Events\Registered;

class SendPhoneNumberVerificationNotification
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        if ($event->user instanceof MustVerifyPhoneNumber && ! $event->user->hasVerifiedPhoneNumber()) {
            $event->user->generatePhoneNumberVerificationToken();
        }
    }
}
