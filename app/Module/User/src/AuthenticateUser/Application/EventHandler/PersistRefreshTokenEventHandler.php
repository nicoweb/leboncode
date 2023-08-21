<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Application\EventHandler;

use NicolasLefevre\LeBonCode\User\AuthenticateUser\Application\AuthenticateUserResult;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Event\RefreshTokenCreatedEvent;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\EventHandler\PersistRefreshTokenEventHandler as IPersistRefreshTokenEventHandler;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Repository\RefreshTokenRepository;

final readonly class PersistRefreshTokenEventHandler implements IPersistRefreshTokenEventHandler
{
    public function __construct(
        private RefreshTokenRepository $repository,
        private AuthenticateUserResult $result,
    ) {
    }

    public function handle(RefreshTokenCreatedEvent $event): void
    {
        $this->repository->save($event->refreshTokenModel, $event->userId);

        $this->result->setAccessToken($event->accessToken);
        $this->result->setRefreshToken($event->refreshToken);
    }
}
