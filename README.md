# Laravel Phone Number Verification

This is a simple package to help verify users phone number. This package uses [alhaji-aki/laravel-otp-token](https://github.com/alhaji-aki/laravel-otp-token) to generate and send otp tokens to users

## Installation

You can install the package via composer by running:

```bash
composer require "alhaji-aki/laravel-phone-number-verification"
```

After the installation has completed, the package will automatically register itself.
Run the following to publish the migration, config and lang file

```bash
php artisan phone-number-verification:install
```

This publishes a `PhoneNumberVerificationController` in `App\Http\Controllers\Auth`.

**_NOTE:_** This package does not work on views so you will have to do some minor changes if you want to display views instead json responses.

Since this package uses [alhaji-aki/laravel-otp-token](https://github.com/alhaji-aki/laravel-otp-token), you will have to publish its files

```bash
php artisan vendor:publish --provider="AlhajiAki\OtpToken\OtpTokenServiceProvider"
```

After publishing the files, you can run migrations:

```bash
php artisan migrate
```

Let your model implement the `CanSendOtpToken` contract and use the `CanSendOtpToken` trait like so:

```php
<?php

namespace App\Models;

use AlhajiAki\OtpToken\Contracts\CanSendOtpToken as CanSendOtpTokenContract;
use AlhajiAki\OtpToken\Traits\CanSendOtpToken;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements CanSendOtpTokenContract
{
    use Notifiable, CanSendOtpToken;
}
```

To learn more about this check out [alhaji-aki/laravel-otp-token](https://github.com/alhaji-aki/laravel-otp-token).

Now let your model implement `MustVerifyPhoneNumber` contract and `MustVerifyPhoneNumber` trait to be able to allow users verify their phone numbers. Like this:

```php
<?php

namespace App\Models;

use AlhajiAki\OtpToken\Contracts\CanSendOtpToken as CanSendOtpTokenContract;
use AlhajiAki\OtpToken\Traits\CanSendOtpToken;
use AlhajiAki\PhoneNumberVerification\Contracts\MustVerifyPhoneNumber as MustVerifyPhoneNumberContract;
use AlhajiAki\PhoneNumberVerification\Traits\MustVerifyPhoneNumber;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements CanSendOtpTokenContract, MustVerifyPhoneNumberContract
{
    use Notifiable, CanSendOtpToken, MustVerifyPhoneNumber;
}
```

For this to work properly, you will have to override three methods in your User model. There are:

- `phoneNumberAttribute()`: This is the attribute that represents the phone number column in your database.
- `phoneNumberVerificationAttribute()`: This is the timestamp attribute that will be updated to indicate that a user is verified.
- `sendPhoneNumberVerificationNotification()`: This is where the notification will be sent. This receives the token to be sent.

An example implementation is:

```php
<?php

namespace App\Models;

use AlhajiAki\OtpToken\Contracts\CanSendOtpToken as CanSendOtpTokenContract;
use AlhajiAki\OtpToken\Traits\CanSendOtpToken;
use AlhajiAki\PhoneNumberVerification\Contracts\MustVerifyPhoneNumber as MustVerifyPhoneNumberContract;
use AlhajiAki\PhoneNumberVerification\Traits\MustVerifyPhoneNumber;
use App\Notifications\Auth\VerifyPhoneNumber;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements CanSendOtpTokenContract, MustVerifyPhoneNumberContract
{
    use Notifiable, CanSendOtpToken, MustVerifyPhoneNumber;

    public function phoneNumberAttribute(): string
    {
        return 'phone_number';
    }

    public function phoneNumberVerificationAttribute(): string
    {
        return 'phone_number_verified_at';
    }

    public function sendPhoneNumberVerificationNotification(string $token): void
    {
        $this->notify(new VerifyPhoneNumber($token));
    }
}
```

If you want users to be notified when they register on your application add `SendPhoneNumberVerificationNotification` listener to listeners of the `Registered` event in the `EventServiceProvider`. Like this:

```php
/**
 * The event listener mappings for the application.
 *
 * @var array<class-string, array<int, class-string>>
 */
protected $listen = [
    ...
    Registered::class => [
        SendPhoneNumberVerificationNotification::class,
    ],
    ...
];
```

Dont forget to import the full namespace `use \AlhajiAki\PhoneNumberVerification\Listeners\SendPhoneNumberVerificationNotification;`

Finally, you will have to register the `\AlhajiAki\PhoneNumberVerification\Middleware\EnsurePhoneNumberIsVerified::class` middleware in your Http Kernel. Example:

```php
protected $routeMiddleware = [
    ...
    'mobile-verified' => \AlhajiAki\PhoneNumberVerification\Middleware\EnsurePhoneNumberIsVerified::class,
    ...
];
```

Then you can protect your routes with that middleware.

## Testing

```bash
composer test
```

## Formatting

```bash
composer format
```

## Static analysis

```bash
composer analyse
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
