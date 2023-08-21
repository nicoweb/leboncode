<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error;

use NicolasLefevre\LeBonCode\Core\Domain\Error\SingleValidationError;

final class LastnameValidationError extends SingleValidationError
{
    public const PROPERTY_PATH = 'lastname';
    public const MESSAGE = 'too_short';

    public static function create(): self
    {
        return new self();
    }
}
