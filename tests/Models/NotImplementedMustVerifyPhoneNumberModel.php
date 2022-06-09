<?php

namespace AlhajiAki\PhoneNumberVerification\Tests\Models;

use AlhajiAki\LaravelUuid\HasUuid;
use AlhajiAki\PhoneNumberVerification\Traits\MustVerifyPhoneNumber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class NotImplementedMustVerifyPhoneNumberModel extends Authenticatable
{
    use MustVerifyPhoneNumber;

    protected $table = 'not_implemented_must_verify_phone_number_models';

    protected $guarded = [];

    public $timestamps = false;
}
