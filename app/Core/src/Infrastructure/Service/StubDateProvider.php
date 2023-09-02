<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Infrastructure\Service;

use DateTimeImmutable;
use NicolasLefevre\LeBonCode\Core\Domain\Service\DateProvider;

final readonly class StubDateProvider implements DateProvider
{
    public const NOW = '2021-01-01 00:00:00';

    public function __construct(
        private DateTimeImmutable $fixedDate,
    ) {
    }

    public function now(): DateTimeImmutable
    {
        return $this->fixedDate;
    }
}
