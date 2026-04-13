<?php

namespace App\Http\Controllers\Auth;

use AlhajiAki\OtpToken\Contracts\OtpTokenBroker;
use AlhajiAki\OtpToken\OtpToken;
use AlhajiAki\PhoneNumberVerification\Contracts\MustVerifyMobile;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class PhoneNumberVerificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Verify the user's phone number.
     *
     * @return Response
     */
    public function verify(Request $request)
    {

        $validated = $request->validate([
            'token' => 'required|string|size:6',
        ]);

        abort_if($request->user()->hasVerifiedPhoneNumber(), Response::HTTP_FORBIDDEN, 'Already verified');

        // Here we will attempt to verify token. If it is successful we
        // will update the mobile verified at on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->performAction(
            array_merge($validated, [
                $request->user()->phoneNumberAttribute() => $request->user()->getPhoneNumberForVerification(),
                'action' => $request->user()->getPhoneNumberVerificationAction(),
                'field' => $request->user()->phoneNumberAttribute(),
            ]),
            function ($user) {
                $this->markAsVerified($user);
            }
        );

        if ($response !== OtpToken::ACTION_COMPLETED) {
            throw ValidationException::withMessages([
                'token' => trans($response),
            ]);
        }

        // If the action was successful, we will return a success
        // response else an error response is sent.
        return response()->json([
            'message' => trans($response),
        ], Response::HTTP_OK);
    }

    /**
     * Resend the phone number verification notification.
     *
     * @return JsonResponse|RedirectResponse
     */
    public function resend(Request $request)
    {
        abort_if($request->user()->hasVerifiedPhoneNumber(), Response::HTTP_FORBIDDEN, 'Already verified');

        $response = $request->user()->generatePhoneNumberVerificationToken();

        return response()->json([
            'message' => trans($response),
        ], $response == OtpToken::OTP_TOKEN_SENT ? Response::HTTP_OK : Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Mark user's mobile as verified.
     *
     * @param  MustVerifyMobile  $user
     * @return void
     */
    protected function markAsVerified($user)
    {
        if ($user->hasVerifiedPhoneNumber()) {
            return;
        }
        if ($user->markPhoneNumberAsVerified()) {
            event(new Verified($user));
        }
    }

    /**
     * Get the broker to be used during verification.
     *
     * @return OtpTokenBroker
     */
    public function broker()
    {
        return OtpToken::broker();
    }
}
