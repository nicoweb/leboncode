<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Application;

use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Entity\Advert;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject\AdvertId;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject\AuthorId;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject\City;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject\Description;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject\PostalCode;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject\Price;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject\Title;

final readonly class AdvertMapper
{
    public static function mapFromCommand(CreateAdvertCommand $command): Advert
    {
        return new Advert(
            AdvertId::fromString($command->id),
            AuthorId::fromString($command->authorId),
            Title::fromString($command->title),
            Description::fromString($command->description),
            Price::fromFloat($command->price),
            PostalCode::fromString($command->postalCode),
            City::fromString($command->city),
        );
    }
}
