<?php

namespace AlhajiAki\PhoneNumberVerification\Contracts;

interface MustVerifyPhoneNumber
{
    /**
     * Determine if the user has verified their mobile.
     *
     * @return bool
     */
    public function hasVerifiedPhoneNumber();

    /**
     * Mark the given user's mobile as verified.
     *
     * @return bool
     */
    public function markPhoneNumberAsVerified();

    /**
     * Send the mobile verification notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPhoneNumberVerificationNotification($token);

    /**
     * Generate the mobile verification token
     *
     * @return void
     */
    public function generatePhoneNumberVerificationToken();

    /**
     * Get the mobile that should be used for verification.
     *
     * @return string
     */
    public function getPhoneNumberForVerification();

    /**
     * Get the action to use when creating the token
     *
     * @return string
     */
    public function getPhoneNumberVerificationAction();

    public function phoneNumberAttribute();

    public function phoneNumberVerificationAttribute();
}
