<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Domain\ValueObject;

use NicolasLefevre\LeBonCode\Core\Domain\Error\IdValidationError;
use Stringable;

abstract readonly class Uuid implements Stringable
{
    protected function __construct(
        protected string $value,
    ) {
    }

    public static function fromString(string $value): static
    {
        self::validate($value);

        return new static($value);
    }

    public static function validate(string $value): void
    {
        if (!preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i', $value)) {
            throw IdValidationError::create()->withViolation();
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
