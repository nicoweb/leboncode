<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Domain\Service;

interface IdGenerator
{
    public function generate(): string;
}
