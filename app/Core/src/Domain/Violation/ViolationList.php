<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Domain\Violation;

final class ViolationList
{
    /**
     * @var Violation[]
     */
    public array $items = [];

    public function add(Violation $violation): void
    {
        $this->items[] = $violation;
    }

    public function hasViolations(): bool
    {
        return count($this->items) > 0;
    }
}
