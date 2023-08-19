<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Infrastructure\PasswordHasher;

use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\PasswordHasher\PasswordHasher;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject\HashedPassword;
use SensitiveParameter;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final readonly class SymfonyPasswordHasher implements PasswordHasher
{
    public function __construct(
        private PasswordHasherInterface $nativePasswordHasher,
    ) {
    }

    public function hash(#[SensitiveParameter] string $plainPassword): HashedPassword
    {
        return HashedPassword::fromString($this->nativePasswordHasher->hash($plainPassword));
    }
}
