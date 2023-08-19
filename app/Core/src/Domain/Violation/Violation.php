<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Domain\Violation;

final readonly class Violation
{
    private function __construct(
        public string $propertyPath,
        public string $message,
    ) {
    }

    public static function create(string $propertyPath, string $message): self
    {
        return new self($propertyPath, $message);
    }
}
