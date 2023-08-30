<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\Tests\Integration\RegisterUser\Infrastructure;

use Doctrine\ORM\EntityManager;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\Email;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\HashedPassword;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\UserId;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\User;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Entity\RegisterUser;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error\UserAlreadyExistsValidationError;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject\Firstname;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject\Lastname;
use NicolasLefevre\LeBonCode\User\RegisterUser\Infrastructure\Repository\DoctrineRegisterUserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class DoctrineRegisterUserRepositoryTest extends KernelTestCase
{
    private DoctrineRegisterUserRepository $repository;
    private EntityManager $entityManager;

    public function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->repository = $container->get(DoctrineRegisterUserRepository::class);

        $this->entityManager = $container
            ->get('doctrine')
            ->getManager()
        ;
    }

    /** @test */
    public function itShouldSaveAUser(): void
    {
        $registerUser = new RegisterUser(
            id: UserId::fromString('9aad4c18-3719-42bb-b757-742dd1068a51'),
            firstname: Firstname::fromString('John'),
            lastname: Lastname::fromString('Doe'),
            email: Email::fromString('john.doe@test.com'),
            password: HashedPassword::fromString('Password123!'),
        );
        $this->repository->save($registerUser);
        $data = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(
                ['email' => 'john.doe@test.com']
            )
        ;

        $this->assertInstanceOf(User::class, $data);
    }

    /** @test */
    public function itShouldThrowExceptionWhenUserAlreadyExists(): void
    {
        $registerUser = new RegisterUser(
            id: UserId::fromString('9aad4c18-3719-42bb-b757-742dd1068a51'),
            firstname: Firstname::fromString('John'),
            lastname: Lastname::fromString('Doe'),
            email: Email::fromString('john.doe@test.com'),
            password: HashedPassword::fromString('Password123!'),
        );
        $this->repository->save($registerUser);
        $this->expectException(UserAlreadyExistsValidationError::class);
        $this->repository->save($registerUser);
    }
}
