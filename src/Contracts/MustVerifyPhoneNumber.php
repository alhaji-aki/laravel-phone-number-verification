<?php

namespace AlhajiAki\PhoneNumberVerification\Contracts;

interface MustVerifyPhoneNumber
{
    /**
     * Determine if the user has verified their mobile.
     */
    public function hasVerifiedPhoneNumber(): bool;

    /**
     * Mark the given user's mobile as verified.
     */
    public function markPhoneNumberAsVerified(): bool;

    /**
     * Send the mobile verification notification.
     */
    public function sendPhoneNumberVerificationNotification(string $token): void;

    /**
     * Generate the mobile verification token
     */
    public function generatePhoneNumberVerificationToken(): string;

    /**
     * Get the mobile that should be used for verification.
     */
    public function getPhoneNumberForVerification(): string;

    /**
     * Get the action to use when creating the token
     */
    public function getPhoneNumberVerificationAction(): string;

    /**
     * Get the phone number that should be used for verification.
     */
    public function phoneNumberAttribute(): string;

    /**
     * Get the column that should be used to mark verification as completed.
     */
    public function phoneNumberVerificationAttribute(): string;
}
