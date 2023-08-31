<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Application;

use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Repository\CreateAdvertRepository;
use NicolasLefevre\LeBonCode\Core\Domain\CommandHandler;

final readonly class CreateAdvertCommandHandler implements CommandHandler
{
    public function __construct(
        private CreateAdvertRepository $advertRepository,
    ) {
    }

    public function __invoke(CreateAdvertCommand $command): void
    {
        $advert = AdvertMapper::mapFromCommand($command);

        $this->advertRepository->save($advert);
    }
}
