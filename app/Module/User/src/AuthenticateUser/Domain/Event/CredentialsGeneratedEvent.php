<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Event;

use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\UserId;

final readonly class CredentialsGeneratedEvent
{
    public function __construct(
        public string $accessToken,
        public string $refreshToken,
        public UserId $userId,
    ) {
    }
}
