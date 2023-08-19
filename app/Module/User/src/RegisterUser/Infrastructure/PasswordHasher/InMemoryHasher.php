<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Infrastructure\PasswordHasher;

use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\PasswordHasher\PasswordHasher;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject\HashedPassword;

final class InMemoryHasher implements PasswordHasher
{
    public function hash(string $plainPassword): HashedPassword
    {
        return HashedPassword::fromString('Hashed_'.$plainPassword);
    }
}
