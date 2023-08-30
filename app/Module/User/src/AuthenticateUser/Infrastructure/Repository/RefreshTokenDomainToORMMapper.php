<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Repository;

use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\RefreshToken as RefreshTokenORM;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\User;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Entity\RefreshToken;
use Symfony\Component\Uid\Uuid;

final class RefreshTokenDomainToORMMapper
{
    public function map(RefreshToken $refreshToken, User $user): RefreshTokenORM
    {
        $refreshTokenEntity = new RefreshTokenORM();

        $refreshTokenEntity->setId(Uuid::fromString((string) $refreshToken->id));
        $refreshTokenEntity->setUser($user);
        $refreshTokenEntity->setToken((string) $refreshToken->token);
        $refreshTokenEntity->setExpiredAt($refreshToken->expiredAt);

        return $refreshTokenEntity;
    }
}
