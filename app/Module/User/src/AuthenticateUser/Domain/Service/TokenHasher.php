<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Service;

use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\ValueObject\HashedToken;

interface TokenHasher
{
    public function hash(string $value): HashedToken;
}
