<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error;

final class FirstnameValidationError extends SingleValidationError
{
    public const PROPERTY_PATH = 'firstname';
    public const MESSAGE = 'too_short';

    public static function create(): self
    {
        return new self();
    }
}
