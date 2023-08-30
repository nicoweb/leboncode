<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\Tests\Integration\AuthenticateUser\Infrastructure;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\UserId;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\RefreshToken as RefreshTokenEntity;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\User;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Service\StubDateProvider;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Service\StubUuidGenerator;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Entity\RefreshToken;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Error\UserNotFound;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Repository\RefreshTokenRepository;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\ValueObject\HashedToken;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\ValueObject\RefreshTokenId;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Repository\DoctrineRefreshTokenRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

final class DoctrineRefreshTokenRepositoryTest extends KernelTestCase
{
    private const NOW = '2023-01-01T00:00:00+00:00';

    private DoctrineRefreshTokenRepository $refreshTokenRepository;
    private EntityManager $entityManager;
    private StubDateProvider $dateProvider;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->refreshTokenRepository = $container->get(RefreshTokenRepository::class);

        $this->entityManager = $container
            ->get('doctrine')
            ->getManager()
        ;

        $this->dateProvider = new StubDateProvider(new DateTimeImmutable(self::NOW));
    }

    /** @test */
    public function itShouldThrowAnUserNotFoundWhenUserIsNotRegistered(): void
    {
        $this->expectException(UserNotFound::class);

        $this->saveRefreshToken();
    }

    /** @test */
    public function itShouldSaveRefreshTokenForRegisteredUser(): void
    {
        $this->saveUser();
        $this->saveRefreshToken();

        $refreshToken = $this->entityManager
            ->getRepository(RefreshTokenEntity::class)
            ->findOneById(StubUuidGenerator::FIXED_UUID)
        ;

        $this->assertInstanceOf(RefreshTokenEntity::class, $refreshToken);
    }

    private function saveUser(): void
    {
        $user = new User();
        $user->setId(Uuid::fromString(StubUuidGenerator::FIXED_UUID));
        $user->setEmail('john.doe@test.com');
        $user->setFirstname('John');
        $user->setLastname('Doe');
        $user->setPassword('hashed_password');
        $user->setCreatedAt((new DateTime())->setTimestamp($this->dateProvider->now()->getTimestamp()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function saveRefreshToken(): void
    {
        $this->refreshTokenRepository->save(
            new RefreshToken(
                RefreshTokenId::fromString(StubUuidGenerator::FIXED_UUID),
                new HashedToken('hashed_token'),
                $this->dateProvider->now(),
                $this->dateProvider->now()->modify('+1 month')
            ),
            UserId::fromString(StubUuidGenerator::FIXED_UUID)
        );
    }
}
