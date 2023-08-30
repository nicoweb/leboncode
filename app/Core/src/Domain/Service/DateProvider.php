<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Domain\Service;

use DateTimeImmutable;

interface DateProvider
{
    public function now(): DateTimeImmutable;
}
