<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\GetAdvert\Application;

use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Entity\Advert;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Error\AdvertDeletedError;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Handler\GetAdvertQuery;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Handler\QueryHandler;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Repository\AdvertRepository;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\AdvertId;

final readonly class GetAdvertQueryHandler implements QueryHandler
{
    public function __construct(
        private AdvertRepository $advertRepository,
    ) {
    }

    public function handle(GetAdvertQuery $query): Advert
    {
        $advert = $this->advertRepository->getAdvertById(
            AdvertId::fromString($query->advertId),
        );

        if (null !== $advert->deletedAt) {
            throw new AdvertDeletedError();
        }

        return $advert;
    }
}
