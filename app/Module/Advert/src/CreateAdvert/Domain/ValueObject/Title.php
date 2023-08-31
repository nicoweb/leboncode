<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject;

use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error\AdvertTitleTooLongValidationError;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error\AdvertTitleTooShortValidationError;

final readonly class Title
{
    private const MIN_LENGTH = 2;
    private const MAX_LENGTH = 255;

    public string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromString(string $value): self
    {
        self::validate($value);

        return new self($value);
    }

    private static function validate(string $value): void
    {
        if (self::isTitleTooShort($value)) {
            throw AdvertTitleTooShortValidationError::create()->withViolation();
        }

        if (self::isTitleTooLong($value)) {
            throw AdvertTitleTooLongValidationError::create()->withViolation();
        }
    }

    private static function isTitleTooShort(string $value): bool
    {
        return strlen($value) < self::MIN_LENGTH;
    }

    private static function isTitleTooLong(string $value): bool
    {
        return strlen($value) > self::MAX_LENGTH;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
