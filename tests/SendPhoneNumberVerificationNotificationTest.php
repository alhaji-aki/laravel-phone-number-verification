<?php

namespace AlhajiAki\PhoneNumberVerification\Tests;

use AlhajiAki\PhoneNumberVerification\Contracts\MustVerifyPhoneNumber;
use AlhajiAki\PhoneNumberVerification\Listeners\SendPhoneNumberVerificationNotification;
use AlhajiAki\PhoneNumberVerification\Tests\Models\NotImplementedMustVerifyPhoneNumberModel;
use Illuminate\Auth\Events\Registered;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class SendPhoneNumberVerificationNotificationTest extends TestCase
{
    /**
     * @return void
     */
    public function test_user_is_an_instance_of_must_verify_phone_number_and_has_not_verified_phone_number()
    {
        $user = $this->getMockBuilder(MustVerifyPhoneNumber::class)->getMock();
        $user->method('hasVerifiedPhoneNumber')->willReturn(false);
        $user->expects($this->once())->method('generatePhoneNumberVerificationToken');

        $listener = new SendPhoneNumberVerificationNotification;

        $listener->handle(new Registered($user));
    }

    /**
     * @return void
     */
    public function test_user_is_not_an_instance_of_must_verify_phone_number()
    {
        $user = m::mock(NotImplementedMustVerifyPhoneNumberModel::class);
        $user->shouldNotReceive('generatePhoneNumberVerificationToken');

        $listener = new SendPhoneNumberVerificationNotification;

        $listener->handle(new Registered($user));
    }

    /**
     * @return void
     */
    public function test_user_is_an_instance_of_must_verify_phone_number_and_has_verified_phone_number()
    {
        $user = $this->getMockBuilder(MustVerifyPhoneNumber::class)->getMock();
        $user->method('hasVerifiedPhoneNumber')->willReturn(true);
        $user->expects($this->never())->method('generatePhoneNumberVerificationToken');

        $listener = new SendPhoneNumberVerificationNotification;

        $listener->handle(new Registered($user));
    }
}
