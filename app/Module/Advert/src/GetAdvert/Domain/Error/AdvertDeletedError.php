<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Error;

use DomainException;

final class AdvertDeletedError extends DomainException
{
    public function __construct()
    {
        parent::__construct('advert_deleted');
    }
}
