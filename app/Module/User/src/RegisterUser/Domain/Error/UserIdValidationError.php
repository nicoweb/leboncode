<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error;

final class UserIdValidationError extends SingleValidationError
{
    public const PROPERTY_PATH = 'id';
    public const MESSAGE = 'invalid';

    public static function create(): self
    {
        return new self();
    }
}
