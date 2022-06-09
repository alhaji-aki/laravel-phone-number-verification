<?php

namespace AlhajiAki\PhoneNumberVerification\Traits;

use AlhajiAki\OtpToken\OtpToken;

trait MustVerifyPhoneNumber
{
    /**
     * Determine if the user has verified their phone number.
     *
     * @return bool
     */
    public function hasVerifiedPhoneNumber()
    {
        return ! is_null($this->{$this->phoneNumberVerificationAttribute()});
    }

    /**
     * Mark the given user's phone number as verified.
     *
     * @return bool
     */
    public function markPhoneNumberAsVerified()
    {
        return $this->forceFill([
            $this->phoneNumberVerificationAttribute() => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Generate the phone number verification token
     *
     * @return void
     */
    public function generatePhoneNumberVerificationToken()
    {
        return $this->broker()->sendOtpToken([
            $this->phoneNumberAttribute() => $this->getPhoneNumberForVerification(),
            'action' => $this->getPhoneNumberVerificationAction(),
            'field' => $this->phoneNumberAttribute(),
        ], function ($user, $token) {
            $user->sendPhoneNumberVerificationNotification($token);
        });
    }

    /**
     * Get the phone number that should be used for verification.
     *
     * @return string
     */
    public function getPhoneNumberForVerification()
    {
        return $this->{$this->phoneNumberAttribute()};
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \AlhajiAkiOtpToken\Contracts\OtpTokenBroker
     */
    public function broker()
    {
        return OtpToken::broker();
    }

    public function getPhoneNumberVerificationAction()
    {
        return 'account-verification';
    }
}
