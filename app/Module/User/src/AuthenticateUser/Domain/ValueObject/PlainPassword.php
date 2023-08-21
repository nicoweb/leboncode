<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\ValueObject;

use NicolasLefevre\LeBonCode\Core\Domain\Error\PasswordValidationError;
use Stringable;

final readonly class PlainPassword implements Stringable
{
    public function __construct(
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
        $validationError = new PasswordValidationError();

        if (empty($value)) {
            $validationError->addViolation(PasswordValidationError::IS_EMPTY);
        }

        if ($validationError->hasViolations()) {
            throw $validationError;
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
