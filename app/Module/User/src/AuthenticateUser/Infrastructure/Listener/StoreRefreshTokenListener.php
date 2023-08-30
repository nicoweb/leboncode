<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Listener;

use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Event\RefreshTokenCreatedEvent;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\EventHandler\PersistRefreshTokenEventHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class StoreRefreshTokenListener implements EventSubscriberInterface
{
    public function __construct(
        private PersistRefreshTokenEventHandler $persistRefreshTokenEventHandler,
    ) {
    }

    public function __invoke(RefreshTokenCreatedEvent $event): void
    {
        $this->persistRefreshTokenEventHandler->handle($event);
    }

    public static function getSubscribedEvents(): array
    {
        return [RefreshTokenCreatedEvent::class => '__invoke'];
    }
}
