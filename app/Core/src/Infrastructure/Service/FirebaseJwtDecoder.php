<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Infrastructure\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use NicolasLefevre\LeBonCode\Core\Domain\Service\JwtDecoder;
use stdClass;

final readonly class FirebaseJwtDecoder implements JwtDecoder
{
    public function __construct(
        private string $jwtSecret,
        private string $jwtAlgorithm,
    ) {
    }

    public function decode(string $jwt): stdClass
    {
        return JWT::decode(
            jwt: $jwt,
            keyOrKeyArray: new Key($this->jwtSecret, $this->jwtAlgorithm),
        );
    }
}
