<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Application;

final class AuthenticateUserResult
{
    private string $accessToken;
    private string $refreshToken;

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }
}
