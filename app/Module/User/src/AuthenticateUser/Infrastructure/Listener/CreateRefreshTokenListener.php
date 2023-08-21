<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Listener;

use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Event\CredentialsGeneratedEvent;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\EventHandler\CreateRefreshTokenEventHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class CreateRefreshTokenListener implements EventSubscriberInterface
{
    public function __construct(
        private CreateRefreshTokenEventHandler $createRefreshTokenEventHandler,
    ) {
    }

    public function __invoke(CredentialsGeneratedEvent $event): void
    {
        $this->createRefreshTokenEventHandler->handle($event);
    }

    public static function getSubscribedEvents(): array
    {
        return [CredentialsGeneratedEvent::class => '__invoke'];
    }
}
