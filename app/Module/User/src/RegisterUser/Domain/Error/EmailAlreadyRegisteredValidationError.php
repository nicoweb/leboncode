<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error;

use NicolasLefevre\LeBonCode\Core\Domain\Error\SingleValidationError;

final class EmailAlreadyRegisteredValidationError extends SingleValidationError
{
    public const PROPERTY_PATH = 'email';
    public const MESSAGE = 'already_registered';

    public static function create(): self
    {
        return new self();
    }
}
