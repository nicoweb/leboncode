<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Error;

use DomainException;

final class AdvertNotFoundError extends DomainException
{
    public function __construct(string $message = 'advert_not_found')
    {
        parent::__construct($message);
    }
}
