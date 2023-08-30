<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Error;

use DomainException;

final class UserNotFound extends DomainException
{
    public function __construct(string $message = 'user_not_found')
    {
        parent::__construct($message);
    }
}
