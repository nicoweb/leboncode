<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Infrastructure\Service;

use NicolasLefevre\LeBonCode\Core\Domain\Service\IdGenerator;
use Symfony\Component\Uid\Uuid;

final class UuidGenerator implements IdGenerator
{
    public function generate(): string
    {
        return Uuid::v4()->toRfc4122();
    }
}
