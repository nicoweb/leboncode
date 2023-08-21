<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Application\Factory;

use NicolasLefevre\LeBonCode\Core\Domain\Error\ValidationError;
use NicolasLefevre\LeBonCode\Core\Domain\Violation\Violation;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Error\InvalidCredentialsError;

final class ValidationErrorFactory
{
    public static function createInvalidCredentialsError(): ValidationError
    {
        $violation = Violation::create(
            InvalidCredentialsError::PROPERTY_PATH,
            InvalidCredentialsError::MESSAGE,
        );

        return (new ValidationError())->addViolation($violation);
    }
}
