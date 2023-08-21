<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Infrastructure\Service;

use NicolasLefevre\LeBonCode\Core\Domain\Service\IdGenerator;

final class StubUuidGenerator implements IdGenerator
{
    public const FIXED_UUID = '123e4567-e89b-12d3-a456-426614174000';

    public function generate(): string
    {
        return self::FIXED_UUID;
    }
}
