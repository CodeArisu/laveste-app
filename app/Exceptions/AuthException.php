<?php

namespace App\Exceptions;

use App\Enum\StatusCode;
use Throwable;

class AuthException extends InternalExceptions
{
    public function render($request, Throwable $exception) 
    {
        if ($exception instanceOf AuthException)
        {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'auth_error' => $exception->getMessage(),
                    'auth_error_code' => $exception->getCode(),
                ]);
        }
        return self::render($request, $exception);
    }

    public static function userAlreadyRegistered(): self
    {
        return static::new(StatusCode::UserIsAlreadyRegistered);
    }

    public static function invalidUserCredentials(): self
    {
        return static::new(StatusCode::InvalidUserCredential);
    }

    public static function userEmailAlreadyTaken(): self
    {
        return static::new(StatusCode::UserEmailAlreadyTaken);
    }

    public static function incorrectLoginPassword(): self
    {
        return static::new(StatusCode::IncorrectLoginPassword);
    }

    public static function incorrectRetypePassword(): self
    {
        return static::new(StatusCode::IncorrectRetypePassword);
    }

    public static function userRegistrationFailed(): self
    {
        return static::new(StatusCode::UserRegistrationFailed);
    }

    public static function userLoginFailed(): self
    {
        return static::new(StatusCode::UserLoginFailed);
    }

    public static function userNotFound(): self
    {
        return static::new(StatusCode::UserNotFound);
    }

    public static function unauthenticated(): self
    {
        return static::new(StatusCode::Unauthenticated);
    }

    public static function logoutFailed(): self
    {
        return static::new(StatusCode::LogoutFailed);
    }
}
