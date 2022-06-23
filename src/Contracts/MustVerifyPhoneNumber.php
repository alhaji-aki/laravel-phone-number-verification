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
     * @return string
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

    /**
     * Get the phone number that should be used for verification.
     *
     * @return string
     */
    public function phoneNumberAttribute();

    /**
     * Get the column that should be used to mark verification as completed.
     *
     * @return string
     */
    public function phoneNumberVerificationAttribute();
}
