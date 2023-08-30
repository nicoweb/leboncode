<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Service;

use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Entity\AuthUser;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Service\AccessTokenGenerator;

final class StubAccessTokenGenerator implements AccessTokenGenerator
{
    public const ACCESS_TOKEN = 'eyJhbGciOiJIUzI1NiJ9.eyJzdWJqZWN0IjoiNDVhMjMxYjItZGQ1NC00ODM2LWFiYmYtMmRhMWZjYzQ1ZWM4Iiwicm9sZXMiOiJb4oCcUk9MRV9VU0VS4oCdXSIsImV4cCI6IjE2NzI2MTc2MDAiLCJpYXQiOiIxNjcyNTMxMjAwIiwiZW1haWwiOiJqb2huLmRvZUB0ZXN0LmNvbSJ9.6xYwcDk7uyXphZr8pHjKRZDo6zKQj3ueqRHc4b8xStk';

    public function generate(AuthUser $authUser): string
    {
        return self::ACCESS_TOKEN;
    }
}
