<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Repository;

use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\Email;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Repository\UserDoctrineRepository;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Entity\AuthUser;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Error\EmailNotRegistered;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Repository\AuthenticateUserRepository;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Transformer\UserToAuthUserTransformer;

final readonly class DoctrineAuthenticateUserRepository implements AuthenticateUserRepository
{
    public function __construct(
        private UserDoctrineRepository $repository,
        private UserToAuthUserTransformer $authUserTransformer,
    ) {
    }

    public function findOneByEmail(Email $email): AuthUser
    {
        $user = $this->repository->findOneBy(['email' => (string) $email]);

        if (!$user) {
            throw new EmailNotRegistered();
        }

        return $this->authUserTransformer->transform($user);
    }
}
