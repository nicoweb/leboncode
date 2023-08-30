<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Error;

use NicolasLefevre\LeBonCode\Core\Domain\Error\SingleValidationError;

final class InvalidCredentialsError extends SingleValidationError
{
    public const PROPERTY_PATH = 'credentials';
    public const MESSAGE = 'invalid_credentials';

    public static function create(): InvalidCredentialsError
    {
        return (new InvalidCredentialsError())->withViolation();
    }
}
