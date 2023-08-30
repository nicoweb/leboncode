<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\ValueObject;

use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\Email;

final readonly class Credentials
{
    public function __construct(
        public Email $email,
        public PlainPassword $plainPassword,
    ) {
    }
}
