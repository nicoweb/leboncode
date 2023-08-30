<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Repository;

use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\Email;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Entity\AuthUser;

interface AuthenticateUserRepository
{
    public function findOneByEmail(Email $email): AuthUser;
}
