<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error;

use NicolasLefevre\LeBonCode\Core\Domain\Error\SingleValidationError;

final class NonPositivePriceValidationError extends SingleValidationError
{
    public const PROPERTY_PATH = 'price';
    public const MESSAGE = 'non_positive_price';

    public static function create(): self
    {
        return new self();
    }
}
