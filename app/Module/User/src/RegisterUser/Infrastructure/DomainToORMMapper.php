<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Infrastructure;

use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\User;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Entity\RegisterUser;
use Symfony\Component\Uid\Uuid;

final class DomainToORMMapper
{
    public function map(RegisterUser $registerUser): User
    {
        $user = new User();
        $user->setEmail((string) $registerUser->email);
        $user->setPassword((string) $registerUser->password);
        $user->setFirstName((string) $registerUser->firstname);
        $user->setLastName((string) $registerUser->lastname);
        $user->setId(Uuid::fromString((string) $registerUser->id));

        return $user;
    }
}
