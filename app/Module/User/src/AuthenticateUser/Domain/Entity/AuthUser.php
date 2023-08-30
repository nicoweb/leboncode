<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Entity;

use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\Email;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\HashedPassword;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\UserId;

final readonly class AuthUser
{
    public function __construct(
        public UserId $id,
        public Email $email,
        public HashedPassword $hashedPassword,
        /** @var array<string> */
        public array $roles,
    ) {
    }
}
