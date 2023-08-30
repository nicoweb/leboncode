<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\ValueObject;

use Stringable;

final readonly class HashedToken implements Stringable
{
    public function __construct(
        public string $value,
    ) {
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
