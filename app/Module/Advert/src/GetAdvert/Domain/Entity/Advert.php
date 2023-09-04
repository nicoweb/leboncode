<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Entity;

use DateTimeImmutable;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\AdvertId;

final readonly class Advert
{
    public function __construct(
        public AdvertId $id,
        public string $title,
        public string $description,
        public int $price,
        public string $postalCode,
        public string $city,
        public ?DateTimeImmutable $deletedAt,
    ) {
    }
}
