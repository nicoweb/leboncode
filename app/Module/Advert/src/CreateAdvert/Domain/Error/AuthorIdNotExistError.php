<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error;

use DomainException;

final class AuthorIdNotExistError extends DomainException
{
    public function __construct(string $message = 'author_id_not_exist')
    {
        parent::__construct($message);
    }
}
