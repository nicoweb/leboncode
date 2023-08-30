<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Application;

final readonly class AuthenticateUserCommand
{
    private function __construct(
        public string $email,
        public string $plainPassword,
    ) {
    }

    public static function create(string $email, string $plainPassword): self
    {
        return new self($email, $plainPassword);
    }
}
