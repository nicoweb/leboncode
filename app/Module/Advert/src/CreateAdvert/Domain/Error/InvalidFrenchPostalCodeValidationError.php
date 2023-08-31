<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error;

use NicolasLefevre\LeBonCode\Core\Domain\Error\SingleValidationError;

final class InvalidFrenchPostalCodeValidationError extends SingleValidationError
{
    public const PROPERTY_PATH = 'postalCode';
    public const MESSAGE = 'invalid_french_postal_code';

    public static function create(): self
    {
        return new self();
    }
}
