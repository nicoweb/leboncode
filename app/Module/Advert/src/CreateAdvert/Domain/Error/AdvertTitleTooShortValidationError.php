<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error;

use NicolasLefevre\LeBonCode\Core\Domain\Error\SingleValidationError;

final class AdvertTitleTooShortValidationError extends SingleValidationError
{
    public const PROPERTY_PATH = 'title';
    public const MESSAGE = 'too_short';

    public static function create(): self
    {
        return new self();
    }
}
