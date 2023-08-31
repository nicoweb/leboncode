<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Entity;

use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject\AdvertId;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject\AuthorId;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject\City;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject\Description;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject\PostalCode;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject\Price;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject\Title;

final readonly class Advert
{
    public function __construct(
        public AdvertId $id,
        public AuthorId $authorId,
        public Title $title,
        public Description $description,
        public Price $price,
        public PostalCode $postalCode,
        public City $city,
    ) {
    }
}
