<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Domain\ValueObject;

final readonly class UserId extends Uuid
{
    public function equals(UserId $id): bool
    {
        return $this->value === $id->value;
    }
}
