<?php

namespace AlhajiAki\PhoneNumberVerification\Middleware;

use AlhajiAki\PhoneNumberVerification\Contracts\MustVerifyPhoneNumber;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EnsurePhoneNumberIsVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        if (
            ! $request->user() ||
            ! $request->user() instanceof MustVerifyPhoneNumber ||
            ! $request->user()->hasVerifiedPhoneNumber()
        ) {
            abort(Response::HTTP_FORBIDDEN, 'Your phone number is not verified.');
        }

        return $next($request);
    }
}
