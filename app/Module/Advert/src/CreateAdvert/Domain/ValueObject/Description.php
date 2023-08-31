<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject;

use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error\AdvertDescriptionTooShortValidationError;
use Stringable;

final readonly class Description implements Stringable
{
    private const MIN_LENGTH = 200;

    private function __construct(
        public string $value,
    ) {
    }

    public static function fromString(string $value): self
    {
        if (self::isDescriptionTooShort($value)) {
            throw AdvertDescriptionTooShortValidationError::create()->withViolation();
        }

        return new self($value);
    }

    private static function isDescriptionTooShort(string $value): bool
    {
        return strlen($value) < self::MIN_LENGTH;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
