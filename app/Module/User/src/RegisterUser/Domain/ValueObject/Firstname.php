<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject;

use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error\FirstnameValidationError;
use Stringable;

final class Firstname implements Stringable
{
    private function __construct(
        public string $value,
    ) {
    }

    public static function fromString(string $value): self
    {
        self::validate($value);

        return new self($value);
    }

    public static function validate(string $value): void
    {
        if (strlen($value) < 2) {
            throw FirstnameValidationError::create()->withViolation();
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
