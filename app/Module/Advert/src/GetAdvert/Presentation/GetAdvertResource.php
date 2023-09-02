<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\GetAdvert\Presentation;

final readonly class GetAdvertResource
{
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public float $price,
        public string $postalCode,
        public string $city,
    ) {
    }
}
