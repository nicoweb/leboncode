<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\UserId;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\User;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Entity\RefreshToken;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Error\UserNotFound;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Repository\RefreshTokenRepository;

final readonly class DoctrineRefreshTokenRepository implements RefreshTokenRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RefreshTokenDomainToORMMapper $domainToORMMapper,
    ) {
    }

    public function save(RefreshToken $refreshToken, UserId $userId): void
    {
        $user = $this->findUser($userId);

        $refreshTokenEntity = $this->domainToORMMapper->map($refreshToken, $user);

        $this->entityManager->persist($refreshTokenEntity);
        $this->entityManager->flush();
    }

    private function findUser(UserId $userId): User
    {
        $user = $this->entityManager->getRepository(User::class)->find((string) $userId);

        if (!$user instanceof User) {
            throw new UserNotFound();
        }

        return $user;
    }
}
