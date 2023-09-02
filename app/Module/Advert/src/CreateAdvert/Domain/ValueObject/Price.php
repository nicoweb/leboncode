<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject;

use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error\NonPositivePriceValidationError;

final readonly class Price
{
    private function __construct(
        public int $priceInCents,
        public string $currency = 'EUR',
    ) {
    }

    public static function fromFloat(float $value): self
    {
        self::validate($value);

        return new self((int) ($value * 100));
    }

    public static function fromInt(int $value): self
    {
        self::validate($value);

        return new self($value);
    }

    private static function validate(float $value): void
    {
        if ($value <= 0) {
            throw NonPositivePriceValidationError::create()->withViolation();
        }
    }
}
