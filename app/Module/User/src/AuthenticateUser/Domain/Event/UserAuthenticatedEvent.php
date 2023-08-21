<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Event;

use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Entity\AuthUser;

final readonly class UserAuthenticatedEvent
{
    public function __construct(
        public AuthUser $authUser
    ) {
    }
}
