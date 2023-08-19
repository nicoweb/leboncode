<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Domain\PasswordHasher;

use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject\HashedPassword;

interface PasswordHasher
{
    public function hash(string $plainPassword): HashedPassword;
}
