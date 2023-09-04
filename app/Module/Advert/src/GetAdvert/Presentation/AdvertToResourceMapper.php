<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\GetAdvert\Presentation;

use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Entity\Advert;

final class AdvertToResourceMapper
{
    public static function map(Advert $advert): GetAdvertResource
    {
        return new GetAdvertResource(
            (string) $advert->id,
            $advert->title,
            $advert->description,
            floatval($advert->price / 100),
            $advert->postalCode,
            $advert->city,
        );
    }
}
