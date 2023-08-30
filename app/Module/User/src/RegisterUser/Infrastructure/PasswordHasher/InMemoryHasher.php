<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Infrastructure\PasswordHasher;

use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\HashedPassword;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\PasswordHasher\PasswordHasher;

final class InMemoryHasher implements PasswordHasher
{
    public function hash(string $plainPassword): HashedPassword
    {
        return HashedPassword::fromString('Hashed_'.$plainPassword);
    }
}
