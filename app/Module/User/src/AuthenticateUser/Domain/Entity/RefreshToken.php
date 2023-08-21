<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Entity;

use DateTimeImmutable;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\ValueObject\HashedToken;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\ValueObject\RefreshTokenId;

final readonly class RefreshToken
{
    public function __construct(
        public RefreshTokenId $id,
        public HashedToken $token,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $expiredAt,
    ) {
    }
}
