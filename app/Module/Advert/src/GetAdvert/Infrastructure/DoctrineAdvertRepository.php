<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\GetAdvert\Infrastructure;

use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Entity\Advert as AdvertModel;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Error\AdvertNotFoundError;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Repository\AdvertRepository as IAdvertRepository;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\AdvertId;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\Advert;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Repository\AdvertRepository;

final readonly class DoctrineAdvertRepository implements IAdvertRepository
{
    public function __construct(
        private AdvertRepository $advertRepository,
    ) {
    }

    public function getAdvertById(AdvertId $advertId): AdvertModel
    {
        $advert = $this->advertRepository->findOneBy(['id' => (string) $advertId]);

        if (!$advert instanceof Advert) {
            throw new AdvertNotFoundError();
        }

        return AdvertOrmToModelMapper::map($advert);
    }
}
