<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Infrastructure\Repository;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Entity\RegisterUser;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error\UserAlreadyExistsValidationError;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\RegisterUserRepository\RegisterUserRepository;
use NicolasLefevre\LeBonCode\User\RegisterUser\Infrastructure\DomainToORMMapper;

final readonly class DoctrineRegisterUserRepository implements RegisterUserRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DomainToORMMapper $domainToORMMapper,
    ) {
    }

    public function save(RegisterUser $registerUser): void
    {
        $user = $this->domainToORMMapper->map($registerUser);

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException) {
            throw UserAlreadyExistsValidationError::create()->withViolation();
        }
    }
}
