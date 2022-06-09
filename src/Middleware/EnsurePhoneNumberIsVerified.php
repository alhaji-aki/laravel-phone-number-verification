<?php

namespace AlhajiAki\PhoneNumberVerification\Middleware;

use AlhajiAki\PhoneNumberVerification\Contracts\MustVerifyPhoneNumber;
use Closure;
use Illuminate\Http\Response;

class EnsurePhoneNumberIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, Closure $next)
    {
        if (
            !$request->user() ||
            !$request->user() instanceof MustVerifyPhoneNumber ||
            !$request->user()->hasVerifiedPhoneNumber()
        ) {
            abort(Response::HTTP_FORBIDDEN, 'Your phone number is not verified.');
        }

        return $next($request);
    }
}
