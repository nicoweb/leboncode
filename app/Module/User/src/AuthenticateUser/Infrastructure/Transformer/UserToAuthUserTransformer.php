<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Transformer;

use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\Email;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\HashedPassword;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\UserId;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\User;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Entity\AuthUser;

final class UserToAuthUserTransformer
{
    public function transform(User $user): AuthUser
    {
        return new AuthUser(
            UserId::fromString((string) $user->getId()),
            Email::fromString($user->getEmail()),
            HashedPassword::fromString($user->getPassword()),
            $user->getRoles()
        );
    }
}
