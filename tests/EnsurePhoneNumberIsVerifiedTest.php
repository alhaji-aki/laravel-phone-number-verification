<?php

namespace Tests\Unit\Http\Merchants\Middleware;

use AlhajiAki\PhoneNumberVerification\Middleware\EnsurePhoneNumberIsVerified;
use AlhajiAki\PhoneNumberVerification\Tests\Models\ImplementedMustVerifyPhoneNumberModel;
use AlhajiAki\PhoneNumberVerification\Tests\Models\NotImplementedMustVerifyPhoneNumberModel;
use AlhajiAki\PhoneNumberVerification\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

class EnsurePhoneNumberIsVerifiedTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Route::middleware([EnsurePhoneNumberIsVerified::class])
            ->post('/test', function () {
                return 'ok';
            });
    }

    public function test_unauthenticated_users_receive_a_forbidden_response()
    {
        $this->postJson('test')
            ->assertForbidden()
            ->assertJson([
                'message' => 'Your phone number is not verified.',
            ]);
    }

    public function test_authenticated_users_who_have_not_implemented_must_verify_phone_number_contract_receive_a_forbidden_response()
    {
        $user = NotImplementedMustVerifyPhoneNumberModel::create(['phone' => '+233248000000']);

        $this->actingAs($user)
            ->postJson('test')
            ->assertForbidden()
            ->assertJson([
                'message' => 'Your phone number is not verified.',
            ]);
    }

    public function test_authenticated_users_who_have_verified_their_phone_number_gets_a_success_response()
    {
        $user = ImplementedMustVerifyPhoneNumberModel::create(['phone' => '+233248000000', 'phone_verified_at' => now()]);

        $this->actingAs($user)
            ->postJson('test')
            ->assertSuccessful()
            ->assertSee('ok');
    }

    public function test_authenticated_users_who_have_not_verified_their_phone_number_gets_a_forbidden_response()
    {
        $user = ImplementedMustVerifyPhoneNumberModel::create(['phone' => '+233248000000']);

        $this->actingAs($user)
            ->postJson('test')
            ->assertForbidden()
            ->assertJson([
                'message' => 'Your phone number is not verified.',
            ]);
    }
}
