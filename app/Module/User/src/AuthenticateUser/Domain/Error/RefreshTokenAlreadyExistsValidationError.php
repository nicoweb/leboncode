<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Error;

use NicolasLefevre\LeBonCode\Core\Domain\Error\SingleValidationError;

final class RefreshTokenAlreadyExistsValidationError extends SingleValidationError
{
    public const PROPERTY_PATH = 'user';
    public const MESSAGE = 'already_exists';

    public static function create(): self
    {
        return new self();
    }
}
