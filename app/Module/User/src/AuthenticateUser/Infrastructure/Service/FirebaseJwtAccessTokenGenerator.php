<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Service;

use Firebase\JWT\JWT;
use NicolasLefevre\LeBonCode\Core\Domain\Service\DateProvider;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Entity\AuthUser;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Service\AccessTokenGenerator;

final readonly class FirebaseJwtAccessTokenGenerator implements AccessTokenGenerator
{
    public function __construct(
        private DateProvider $dateProvider,
        private int $jwtAccessTokenExpiration,
        private string $jwtSecret,
        private string $jwtAlgorithm,
    ) {
    }

    public function generate(AuthUser $authUser): string
    {
        $payload = $this->getPayload($authUser);

        return JWT::encode($payload, $this->jwtSecret, $this->jwtAlgorithm);
    }

    /**
     * @return array{
     *      subject: string,
     *      roles: string[],
     *      exp: int,
     *      iat: int,
     *      email: string
     *  }
     */
    private function getPayload(AuthUser $authUser): array
    {
        $now = $this->dateProvider->now();
        $future = $now->modify(sprintf('+%d seconds', $this->jwtAccessTokenExpiration));

        return [
            'subject' => (string) $authUser->id,
            'roles' => $authUser->roles,
            'exp' => $future->getTimestamp(),
            'iat' => $now->getTimestamp(),
            'email' => (string) $authUser->email,
        ];
    }
}
