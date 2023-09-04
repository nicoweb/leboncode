<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Repository;

use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Entity\Advert;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\AdvertId;

interface AdvertRepository
{
    public function getAdvertById(AdvertId $advertId): Advert;
}
