<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Application;

final readonly class CreateAdvertCommand
{
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public float $price,
        public string $postalCode,
        public string $city,
        public string $authorId,
    ) {
    }
}
