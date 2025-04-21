<?php

namespace AlhajiAki\PhoneNumberVerification\Tests\Models;

use AlhajiAki\PhoneNumberVerification\Contracts\MustVerifyPhoneNumber as ContractsMustVerifyPhoneNumber;
use AlhajiAki\PhoneNumberVerification\Traits\MustVerifyPhoneNumber;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ImplementedMustVerifyPhoneNumberModel extends Authenticatable implements ContractsMustVerifyPhoneNumber
{
    use MustVerifyPhoneNumber;

    protected $table = 'implemented_must_verify_phone_number_models';

    protected $guarded = [];

    public $timestamps = false;

    public function phoneNumberAttribute(): string
    {
        return 'phone';
    }

    public function phoneNumberVerificationAttribute(): string
    {
        return 'phone_verified_at';
    }

    public function sendPhoneNumberVerificationNotification(string $token): void {}
}
