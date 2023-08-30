<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Repository;

use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\UserId;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Entity\RefreshToken;

interface RefreshTokenRepository
{
    public function save(RefreshToken $refreshToken, UserId $userId): void;
}
