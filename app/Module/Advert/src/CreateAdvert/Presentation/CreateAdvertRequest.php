<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Presentation;

final readonly class CreateAdvertRequest
{
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public float $price,
        public string $city,
        public string $postalCode,
    ) {
    }
}
