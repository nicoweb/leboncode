<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Domain\Error;

use DomainException;
use NicolasLefevre\LeBonCode\Core\Domain\Violation\Violation;
use NicolasLefevre\LeBonCode\Core\Domain\Violation\ViolationList;

final class PasswordValidationError extends DomainException
{
    public const PROPERTY_PATH = 'password';
    public const TOO_SHORT = 'too_short';
    public const TOO_LONG = 'too_long';
    public const NO_DIGIT = 'no_digit';
    public const NO_LOWERCASE = 'no_lowercase';
    public const NO_UPPERCASE = 'no_uppercase';
    public const NO_SPECIAL_CHARACTER = 'no_special_character';
    public const IS_EMPTY = 'is_empty';

    public readonly ViolationList $violations;

    public function __construct(
    ) {
        $this->violations = new ViolationList();
        parent::__construct();
    }

    public function addViolation(string $description): void
    {
        $this->violations->add(Violation::create(self::PROPERTY_PATH, $description));
    }

    public function hasViolations(): bool
    {
        return $this->violations->hasViolations();
    }
}
