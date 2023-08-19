<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject;

use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error\UserIdValidationError;
use Stringable;

final readonly class UserId implements Stringable
{
    private function __construct(
        private string $value,
    ) {
    }

    public static function fromString(string $value): self
    {
        self::validate($value);

        return new self($value);
    }

    public static function validate(string $value): void
    {
        if (!preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i', $value)) {
            throw UserIdValidationError::create()->withViolation();
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(UserId $id): bool
    {
        return $this->value === $id->value;
    }
}
