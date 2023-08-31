<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Repository;

use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Entity\Advert;

interface CreateAdvertRepository
{
    public function save(Advert $advert): void;
}
