<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error;

final class UserAlreadyExistsValidationError extends SingleValidationError
{
    public const PROPERTY_PATH = 'user';
    public const MESSAGE = 'already_exists';

    public static function create(): self
    {
        return new self();
    }
}
