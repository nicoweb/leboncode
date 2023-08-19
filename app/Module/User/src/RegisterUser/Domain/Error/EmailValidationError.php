<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error;

final class EmailValidationError extends SingleValidationError
{
    public const PROPERTY_PATH = 'email';
    public const MESSAGE = 'not_valid';

    public static function create(): self
    {
        return new self();
    }
}
