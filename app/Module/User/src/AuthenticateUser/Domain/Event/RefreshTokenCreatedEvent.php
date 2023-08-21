<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Event;

use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\UserId;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Entity\RefreshToken;

final readonly class RefreshTokenCreatedEvent
{
    public function __construct(
        public RefreshToken $refreshTokenModel,
        public string $accessToken,
        public string $refreshToken,
        public UserId $userId,
    ) {
    }
}
