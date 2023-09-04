<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\GetAdvert\Infrastructure;

use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Entity\Advert;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Error\AdvertNotFoundError;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Repository\AdvertRepository;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\AdvertId;

final class InMemoryAdvertRepository implements AdvertRepository
{
    /**
     * @var array<string, Advert>
     */
    public array $adverts = [];

    public function getAdvertById(AdvertId $advertId): Advert
    {
        if (!isset($this->adverts[(string) $advertId])) {
            throw new AdvertNotFoundError();
        }

        return $this->adverts[(string) $advertId];
    }
}
