<?php

use AlhajiAki\PhoneNumberVerification\Contracts\MustVerifyPhoneNumber;
use AlhajiAki\PhoneNumberVerification\Listeners\SendPhoneNumberVerificationNotification;
use AlhajiAki\PhoneNumberVerification\Tests\Models\NotImplementedMustVerifyPhoneNumberModel;
use Illuminate\Auth\Events\Registered;
use Mockery as m;

test('user is an instance of must verify phone number and has not verified phone number', function () {
    $user = $this->getMockBuilder(MustVerifyPhoneNumber::class)->getMock();
    $user->method('hasVerifiedPhoneNumber')->willReturn(false);
    $user->expects($this->once())->method('generatePhoneNumberVerificationToken');

    $listener = new SendPhoneNumberVerificationNotification;

    $listener->handle(new Registered($user));
});

test('user is not an instance of must verify phone number', function () {
    $user = m::mock(NotImplementedMustVerifyPhoneNumberModel::class);
    $user->shouldNotReceive('generatePhoneNumberVerificationToken');

    $listener = new SendPhoneNumberVerificationNotification;

    $listener->handle(new Registered($user));
});

test('user is an instance of must verify phone number and has verified phone number', function () {
    $user = $this->getMockBuilder(MustVerifyPhoneNumber::class)->getMock();
    $user->method('hasVerifiedPhoneNumber')->willReturn(true);
    $user->expects($this->never())->method('generatePhoneNumberVerificationToken');

    $listener = new SendPhoneNumberVerificationNotification;

    $listener->handle(new Registered($user));
});
