<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error;

final class UserIdAlreadyExistsValidationError extends SingleValidationError
{
    public const PROPERTY_PATH = 'user_id';
    public const MESSAGE = 'already_exists';

    public static function create(): self
    {
        return new self();
    }
}
