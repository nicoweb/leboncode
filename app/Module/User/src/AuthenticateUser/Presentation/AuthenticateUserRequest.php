<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Presentation;

final readonly class AuthenticateUserRequest
{
    public function __construct(
        public string $email,
        public string $password,
    ) {
    }
}
