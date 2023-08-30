<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Repository;

use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\UserId;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Entity\RefreshToken;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Repository\RefreshTokenRepository;

final class InMemoryRefreshTokenRepository implements RefreshTokenRepository
{
    /**
     * @var RefreshToken[]
     */
    private array $refreshTokens = [];

    public function save(RefreshToken $refreshToken, UserId $userId): void
    {
        $this->refreshTokens[(string) $refreshToken->id] = $refreshToken;
    }

    /**
     * @return RefreshToken[]
     */
    public function findAll(): array
    {
        return $this->refreshTokens;
    }
}
