<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Service;

use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Service\TokenHasher;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\ValueObject\HashedToken;

final class StubTokenHasher implements TokenHasher
{
    public function hash(string $value): HashedToken
    {
        return new HashedToken("hashed_$value");
    }
}
