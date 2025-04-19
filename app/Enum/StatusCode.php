<?php

namespace App\Enum;

enum StatusCode : int
{   
    public function getStatusCode(): int
    {
        $value = $this->value;
        // return equivalence of exceeding throwable status
        return match(true)
        {
            $value >= 10_000 => 401,
            $value >= 11_000 => 202,
            default => 500,
        };
    }

    public function getMessage(): string
    {  
        $key = "Exceptions.{$this->value}.message";
        $translations = __($key);

        if ($key === $translations) {
            return 'something went wrong';
        }

        return $translations;
    }

    public function getDescription(): string
    {  
        $key = "Exceptions.{$this->value}.description";
        $translations = __($key);

        if ($key === $translations) {
            return 'no further description added';
        }

        return $translations;
    }

    // error responses
    case UserIsAlreadyRegistered = 10_000;
    case InvalidUserCredential = 10_001;
    case UserEmailAlreadyTaken = 10_002;
    case IncorrectLoginPassword = 10_003;
    case IncorrectRetypePassword = 10_004;
    case UserRegistrationFailed = 10_005;
    case UserLoginFailed = 10_006;
    case UserNotFound = 10_007;
    case Unauthenticated = 10_008;
    case LogoutFailed = 10_009;

    // product error responses
    case ProductNotFound = 12_000;
    case ProductAlreadyAdded = 12_001;
    case ProductCannotBeAdded = 12_002;
    case ProductCreateFailed = 12_003;
    case ProductValidationFailed = 12_004;
    case ProductUpdateFailed = 12_005;
    case ProductDeleteFailed = 12_006;

    // garment error responses
    case GarmentNotFound = 13_000;
    case GarmentAlreadyAdded = 13_001;
    case GarmentCannotBeAdded = 13_002;
    case GarmentCreateFailed = 13_003;
    case GarmentValidationFailed = 13_004;
    case GarmentUpdateFailed = 13_005;
    case GarmentDeleteFailed = 13_006;
}