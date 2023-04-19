<?php

namespace AlhajiAki\PhoneNumberVerification\Traits;

use AlhajiAki\OtpToken\Contracts\OtpTokenBroker;
use AlhajiAki\OtpToken\OtpToken;

trait MustVerifyPhoneNumber
{
    /**
     * Determine if the user has verified their phone number.
     */
    public function hasVerifiedPhoneNumber(): bool
    {
        return ! is_null($this->{$this->phoneNumberVerificationAttribute()});
    }

    /**
     * Mark the given user's phone number as verified.
     */
    public function markPhoneNumberAsVerified(): bool
    {
        return $this->forceFill([
            $this->phoneNumberVerificationAttribute() => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Generate the phone number verification token
     */
    public function generatePhoneNumberVerificationToken(): string
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
     */
    public function getPhoneNumberForVerification(): string
    {
        return $this->{$this->phoneNumberAttribute()};
    }

    /**
     * Get the broker to be used during password reset.
     */
    public function broker(): OtpTokenBroker
    {
        return OtpToken::broker();
    }

    /**
     * Get the action that should be used for verification.
     */
    public function getPhoneNumberVerificationAction(): string
    {
        return 'account-verification';
    }
}
