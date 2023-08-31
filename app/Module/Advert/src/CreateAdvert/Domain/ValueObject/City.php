<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject;

use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error\CityNameTooShortValidationError;
use Stringable;

final class City implements Stringable
{
    private const MIN_LENGTH = 2;

    private function __construct(
        public string $value,
    ) {
    }

    public static function fromString(string $value): self
    {
        if (self::isCityNameTooShort($value)) {
            throw CityNameTooShortValidationError::create()->withViolation();
        }

        return new self($value);
    }

    private static function isCityNameTooShort(string $value): bool
    {
        return strlen($value) < self::MIN_LENGTH;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
