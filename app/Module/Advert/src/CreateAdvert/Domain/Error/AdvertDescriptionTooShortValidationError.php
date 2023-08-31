<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error;

use NicolasLefevre\LeBonCode\Core\Domain\Error\SingleValidationError;

final class AdvertDescriptionTooShortValidationError extends SingleValidationError
{
    public const PROPERTY_PATH = 'description';
    public const MESSAGE = 'too_short';

    public static function create(): self
    {
        return new self();
    }
}
