<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Domain\ValueObject;

use NicolasLefevre\LeBonCode\Core\Domain\Error\EmailValidationError;
use Stringable;

final readonly class Email implements Stringable
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
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw EmailValidationError::create()->withViolation();
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(Email $email): bool
    {
        return $this->value === $email->value;
    }
}
