<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Infrastructure\Service;

use DateTimeImmutable;
use NicolasLefevre\LeBonCode\Core\Domain\Service\DateProvider;

final class SystemDateProvider implements DateProvider
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
