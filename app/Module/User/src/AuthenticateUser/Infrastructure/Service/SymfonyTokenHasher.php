<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Service;

use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Service\TokenHasher;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\ValueObject\HashedToken;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final readonly class SymfonyTokenHasher implements TokenHasher
{
    public function __construct(
        private PasswordHasherInterface $hasher,
    ) {
    }

    public function hash(string $value): HashedToken
    {
        $hashToken = $this->hasher->hash($value);

        return new HashedToken($hashToken);
    }
}
