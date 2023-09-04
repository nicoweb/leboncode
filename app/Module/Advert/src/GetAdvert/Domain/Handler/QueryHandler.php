<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Handler;

use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Entity\Advert;

interface QueryHandler
{
    public function handle(GetAdvertQuery $query): Advert;
}
