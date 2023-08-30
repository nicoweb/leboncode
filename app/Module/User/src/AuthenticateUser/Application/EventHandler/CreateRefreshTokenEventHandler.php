<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Application\EventHandler;

use DateInterval;
use NicolasLefevre\LeBonCode\Core\Domain\EventDispatcher;
use NicolasLefevre\LeBonCode\Core\Domain\Service\DateProvider;
use NicolasLefevre\LeBonCode\Core\Domain\Service\IdGenerator;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Entity\RefreshToken;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Event\CredentialsGeneratedEvent;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Event\RefreshTokenCreatedEvent;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\EventHandler\CreateRefreshTokenEventHandler as ICreateRefreshTokenEventHandler;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Service\TokenHasher;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\ValueObject\RefreshTokenId;

final readonly class CreateRefreshTokenEventHandler implements ICreateRefreshTokenEventHandler
{
    public function __construct(
        private TokenHasher $hasher,
        private IdGenerator $idGenerator,
        private DateProvider $dateProvider,
        private string $refreshTokenExpiration,
        private EventDispatcher $eventDispatcher,
    ) {
    }

    public function handle(CredentialsGeneratedEvent $event): void
    {
        $accessToken = $event->accessToken;
        $refreshToken = $event->refreshToken;

        $hashedRefreshToken = $this->hasher->hash($refreshToken);
        $intervalRefreshToken = new DateInterval('PT'.$this->refreshTokenExpiration.'S');

        $refreshTokenModel = new RefreshToken(
            RefreshTokenId::fromString($this->idGenerator->generate()),
            $hashedRefreshToken,
            $this->dateProvider->now(),
            $this->dateProvider->now()->add($intervalRefreshToken),
        );

        $this->eventDispatcher->dispatch(
            new RefreshTokenCreatedEvent(
                $refreshTokenModel,
                $accessToken,
                $refreshToken,
                $event->userId,
            )
        );
    }
}
