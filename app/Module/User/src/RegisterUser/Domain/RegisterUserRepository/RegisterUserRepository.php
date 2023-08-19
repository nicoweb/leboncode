<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Domain\RegisterUserRepository;

use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Entity\RegisterUser;

interface RegisterUserRepository
{
    public function save(RegisterUser $registerUser): void;
}
