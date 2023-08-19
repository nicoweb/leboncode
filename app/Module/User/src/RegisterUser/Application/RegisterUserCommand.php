<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Application;

final readonly class RegisterUserCommand
{
    private function __construct(
        public string $id,
        public string $firstname,
        public string $lastname,
        public string $email,
        public string $plainPassword,
    ) {
    }

    public static function create(
        string $id,
        string $firstname,
        string $lastname,
        string $email,
        string $plainPassword,
    ): self {
        return new self($id, $firstname, $lastname, $email, $plainPassword);
    }
}
