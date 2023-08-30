<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Service;

use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\HashedPassword;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\ValueObject\PlainPassword;

interface PasswordMatcher
{
    public function match(PlainPassword $plainPassword, HashedPassword $hashedPassword): void;
}
