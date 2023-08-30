<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Application\EventHandler;

use NicolasLefevre\LeBonCode\Core\Domain\EventDispatcher;
use NicolasLefevre\LeBonCode\Core\Domain\Service\IdGenerator;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Event\CredentialsGeneratedEvent;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Event\UserAuthenticatedEvent;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\EventHandler\GenerateCredentialsEventHandler as IGenerateCredentialsEventHandler;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Service\AccessTokenGenerator;

final readonly class GenerateCredentialsEventHandler implements IGenerateCredentialsEventHandler
{
    public function __construct(
        private IdGenerator $idGenerator,
        private AccessTokenGenerator $accessTokenGenerator,
        private EventDispatcher $eventDispatcher
    ) {
    }

    public function handle(UserAuthenticatedEvent $event): void
    {
        $accessToken = $this->accessTokenGenerator->generate($event->authUser);
        $refreshToken = $this->idGenerator->generate();

        $this->eventDispatcher->dispatch(
            new CredentialsGeneratedEvent(
                $accessToken,
                $refreshToken,
                $event->authUser->id,
            )
        );
    }
}
