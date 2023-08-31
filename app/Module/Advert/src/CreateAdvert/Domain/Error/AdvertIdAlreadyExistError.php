<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error;

use DomainException;

final class AdvertIdAlreadyExistError extends DomainException
{
    public function __construct(string $message = 'advert_id_already_exist')
    {
        parent::__construct($message);
    }
}
