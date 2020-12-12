1. composer require
2. run `php artisan phone-number-verification:install` command to publish controller
3. model implements MustVerifyPhoneNumber contract
4. model uses MustVerifyPhoneNumber trait
5. override the phoneNumberAttribute() and phoneNumberVerificationAttribute() methods in the model
6. also override sendPhoneNumberVerificationNotification() methods in the model
7. register middleware

TODO: add tests
