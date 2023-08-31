<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Presentation;

use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Application\CreateAdvertCommand;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\User;

final class CreateAdvertCommandFactory
{
    public static function fromRequest(
        CreateAdvertRequest $request,
        User $user,
    ): CreateAdvertCommand {
        return new CreateAdvertCommand(
            $request->id,
            $request->title,
            $request->description,
            $request->price,
            $request->postalCode,
            $request->city,
            $user->getId()->toRfc4122(),
        );
    }
}
