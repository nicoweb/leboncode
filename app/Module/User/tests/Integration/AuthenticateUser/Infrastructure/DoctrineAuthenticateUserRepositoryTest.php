<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\Tests\Integration\AuthenticateUser\Infrastructure;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\Email;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\User;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Service\StubDateProvider;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Service\StubUuidGenerator;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Entity\AuthUser;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Error\EmailNotRegistered;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Repository\AuthenticateUserRepository;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Repository\DoctrineAuthenticateUserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

final class DoctrineAuthenticateUserRepositoryTest extends KernelTestCase
{
    private const NOW = '2023-01-01T00:00:00+00:00';

    private DoctrineAuthenticateUserRepository $repository;
    private EntityManager $entityManager;
    private StubDateProvider $dateProvider;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->repository = $container->get(AuthenticateUserRepository::class);

        $this->entityManager = $container
            ->get('doctrine')
            ->getManager()
        ;

        $this->dateProvider = new StubDateProvider(new DateTimeImmutable(self::NOW));
    }

    /** @test */
    public function itShouldThrowAnEmailNotRegisteredWhenUserIsNotRegistered(): void
    {
        $email = Email::fromString('not-registered-user@test.com');

        $this->expectException(EmailNotRegistered::class);

        $this->repository->findOneByEmail($email);
    }

    /** @test */
    public function itShouldReturnAUserWhenUserIsAlreadyRegistered(): void
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

        $email = Email::fromString('john.doe@test.com');

        $authUser = $this->repository->findOneByEmail($email);

        $this->assertInstanceOf(AuthUser::class, $authUser);
        $this->assertSame('john.doe@test.com', (string) $authUser->email);
    }
}
