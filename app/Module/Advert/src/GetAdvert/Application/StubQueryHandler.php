<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\GetAdvert\Application;

use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Entity\Advert;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Error\AdvertDeletedError;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Error\AdvertNotFoundError;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Handler\GetAdvertQuery;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Handler\QueryHandler;
use NicolasLefevre\LeBonCode\Core\Domain\Error\IdValidationError;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\AdvertId;

final class StubQueryHandler implements QueryHandler
{
    public function handle(GetAdvertQuery $query): Advert
    {
        if ('invalid-id' === $query->advertId) {
            throw IdValidationError::create()->withViolation();
        }

        if ('not-found-id' === $query->advertId) {
            throw new AdvertNotFoundError();
        }

        if ('deleted-id' === $query->advertId) {
            throw new AdvertDeletedError();
        }

        return new Advert(
            AdvertId::fromString($query->advertId),
            'title',
            'description',
            1000,
            '75000',
            'paris',
            null
        );
    }
}
