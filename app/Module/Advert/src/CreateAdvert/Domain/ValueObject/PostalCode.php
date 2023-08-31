<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject;

use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error\InvalidFrenchPostalCodeValidationError;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\FrenchPostalCode;
use Stringable;

final class PostalCode implements Stringable
{
    private function __construct(
        public string $value,
    ) {
    }

    public static function fromString(string $value): self
    {
        if (false === FrenchPostalCode::isValid($value)) {
            throw InvalidFrenchPostalCodeValidationError::create()->withViolation();
        }

        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
