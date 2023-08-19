<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error;

final class LastnameValidationError extends SingleValidationError
{
    public const PROPERTY_PATH = 'lastname';
    public const MESSAGE = 'too_short';

    public static function create(): self
    {
        return new self();
    }
}
