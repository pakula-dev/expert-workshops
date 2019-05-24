<?php


namespace App\Helpers;


class ErrorsCodesHelper
{
    const EMAIL_REQUIRED = 1000;
    const PASSWORD_REQUIRED = 1001;
    const WRONG_EMAIL_OR_PASSWORD = 1002;
    const TOO_MUCH_LOGIN_ATTEMPTS = 1003;
}