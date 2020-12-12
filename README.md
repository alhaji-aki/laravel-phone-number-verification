1. composer require
2. vendor publish
3. model implements MustVerifyPhoneNumber contract
4. model uses MustVerifyPhoneNumber trait
5. override the phoneNumberAttribute() and phoneNumberVerificationAttribute() methods in the model
6. also override sendPhoneNumberVerificationNotification() methods in the model
