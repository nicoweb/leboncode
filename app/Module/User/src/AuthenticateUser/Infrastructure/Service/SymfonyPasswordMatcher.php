<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Service;

use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\HashedPassword;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Error\PasswordDoesNotMatchError;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Service\PasswordMatcher;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\ValueObject\PlainPassword;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final readonly class SymfonyPasswordMatcher implements PasswordMatcher
{
    public function __construct(
        private PasswordHasherInterface $hasher,
    ) {
    }

    public function match(PlainPassword $plainPassword, HashedPassword $hashedPassword): void
    {
        if (!$this->hasher->verify((string) $hashedPassword, (string) $plainPassword)) {
            throw new PasswordDoesNotMatchError();
        }
    }
}
