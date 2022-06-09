<?php

namespace AlhajiAki\PhoneNumberVerification\Tests\Models;

use AlhajiAki\PhoneNumberVerification\Contracts\MustVerifyPhoneNumber as ContractsMustVerifyPhoneNumber;
use AlhajiAki\PhoneNumberVerification\Traits\MustVerifyPhoneNumber;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ImplementedMustVerifyPhoneNumberModel extends Authenticatable implements ContractsMustVerifyPhoneNumber
{
    use MustVerifyPhoneNumber;

    protected $table = 'not_implemented_must_verify_phone_number_models';

    protected $guarded = [];

    public $timestamps = false;

    public function phoneNumberAttribute()
    {
        return 'phone';
    }

    public function phoneNumberVerificationAttribute()
    {
        return 'phone_verified_at';
    }

    public function sendPhoneNumberVerificationNotification($token)
    {
        return;
    }
}
