<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Infrastructure;

use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Entity\Advert as AdvertDomain;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\Advert as AdvertORM;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\User;
use Symfony\Component\Uid\Uuid;

final class AdvertORMMapper
{
    public static function mapFromDomain(AdvertDomain $advert, User $user): AdvertORM
    {
        $advertORM = new AdvertORM();
        $advertORM->setId(Uuid::fromString((string) $advert->id));
        $advertORM->setUser($user);
        $advertORM->setTitle((string) $advert->title);
        $advertORM->setDescription((string) $advert->description);
        $advertORM->setPrice($advert->price->priceInCents);
        $advertORM->setPostalCode((string) $advert->postalCode);
        $advertORM->setCity((string) $advert->city);

        return $advertORM;
    }
}
