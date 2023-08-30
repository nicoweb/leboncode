<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Service;

use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\HashedPassword;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Error\PasswordDoesNotMatchError;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Service\PasswordMatcher;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\ValueObject\PlainPassword;

final class StubPasswordMatcher implements PasswordMatcher
{
    public function match(PlainPassword $plainPassword, HashedPassword $hashedPassword): void
    {
        if ('hashed_'.$plainPassword !== (string) $hashedPassword) {
            throw new PasswordDoesNotMatchError();
        }
    }
}
