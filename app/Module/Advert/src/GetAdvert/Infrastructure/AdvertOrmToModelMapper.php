<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\GetAdvert\Infrastructure;

use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Entity\Advert as AdvertModel;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\AdvertId;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\Advert as AdvertOrm;

final class AdvertOrmToModelMapper
{
    public static function map(AdvertOrm $advertOrm): AdvertModel
    {
        return new AdvertModel(
            AdvertId::fromString($advertOrm->getId()->toRfc4122()),
            $advertOrm->getTitle(),
            $advertOrm->getDescription(),
            $advertOrm->getPrice(),
            $advertOrm->getPostalCode(),
            $advertOrm->getCity(),
            $advertOrm->getDeletedAt(),
        );
    }
}
