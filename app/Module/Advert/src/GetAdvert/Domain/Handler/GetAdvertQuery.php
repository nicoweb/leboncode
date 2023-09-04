<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Handler;

final readonly class GetAdvertQuery
{
    public function __construct(
        public string $advertId,
    ) {
    }
}
