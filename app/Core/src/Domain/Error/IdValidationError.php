<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Domain\Error;

final class IdValidationError extends SingleValidationError
{
    public const PROPERTY_PATH = 'id';
    public const MESSAGE = 'invalid';

    public static function create(): self
    {
        return new self();
    }
}
