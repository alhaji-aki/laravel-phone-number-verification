<?php

use AlhajiAki\PhoneNumberVerification\Middleware\EnsurePhoneNumberIsVerified;
use AlhajiAki\PhoneNumberVerification\Tests\Models\ImplementedMustVerifyPhoneNumberModel;
use AlhajiAki\PhoneNumberVerification\Tests\Models\NotImplementedMustVerifyPhoneNumberModel;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Route::middleware([EnsurePhoneNumberIsVerified::class])
        ->post('/test', function () {
            return 'ok';
        });
});

test('unauthenticated users receive a forbidden response', function () {
    $this->postJson('test')
        ->assertForbidden()
        ->assertJson([
            'message' => 'Your phone number is not verified.',
        ]);
});

test('authenticated users who have not implemented must verify phone number contract receive a forbidden response', function () {
    $user = NotImplementedMustVerifyPhoneNumberModel::create(['phone' => '+233248000000']);

    $this->actingAs($user)
        ->postJson('test')
        ->assertForbidden()
        ->assertJson([
            'message' => 'Your phone number is not verified.',
        ]);
});

test('authenticated users who have verified their phone number gets a success response', function () {
    $user = ImplementedMustVerifyPhoneNumberModel::create(['phone' => '+233248000000', 'phone_verified_at' => now()]);

    $this->actingAs($user)
        ->postJson('test')
        ->assertSuccessful()
        ->assertSee('ok');
});

test('authenticated users who have not verified their phone number gets a forbidden response', function () {
    $user = ImplementedMustVerifyPhoneNumberModel::create(['phone' => '+233248000000']);

    $this->actingAs($user)
        ->postJson('test')
        ->assertForbidden()
        ->assertJson([
            'message' => 'Your phone number is not verified.',
        ]);
});
