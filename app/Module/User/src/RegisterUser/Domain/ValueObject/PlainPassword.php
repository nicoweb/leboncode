<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject;

use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error\PasswordValidationError;
use Stringable;

final readonly class PlainPassword implements Stringable
{
    private const MIN_LENGTH = 8;
    private const MAX_LENGTH = 250;

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
        $validationError = new PasswordValidationError();

        if (strlen($value) < self::MIN_LENGTH) {
            $validationError->addViolation(PasswordValidationError::TOO_SHORT);
        }

        if (strlen($value) > self::MAX_LENGTH) {
            $validationError->addViolation(PasswordValidationError::TOO_LONG);
        }

        if (!preg_match('/[A-Z]/', $value)) {
            $validationError->addViolation(PasswordValidationError::NO_UPPERCASE);
        }

        if (!preg_match('/[a-z]/', $value)) {
            $validationError->addViolation(PasswordValidationError::NO_LOWERCASE);
        }

        if (!preg_match('/[0-9]/', $value)) {
            $validationError->addViolation(PasswordValidationError::NO_DIGIT);
        }

        if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $value)) {
            $validationError->addViolation(PasswordValidationError::NO_SPECIAL_CHARACTER);
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
