<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Service;

use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Entity\AuthUser;

interface AccessTokenGenerator
{
    public function generate(AuthUser $authUser): string;
}
