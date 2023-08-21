<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Infrastructure\Service;

use DateTimeImmutable;
use NicolasLefevre\LeBonCode\Core\Domain\Service\DateProvider;

final readonly class StubDateProvider implements DateProvider
{
    public function __construct(
        private DateTimeImmutable $fixedDate,
    ) {
    }

    public function now(): DateTimeImmutable
    {
        return $this->fixedDate;
    }
}
