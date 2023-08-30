<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Listener;

use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Event\UserAuthenticatedEvent;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\EventHandler\GenerateCredentialsEventHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class GenerateCredentialsListener implements EventSubscriberInterface
{
    public function __construct(
        private GenerateCredentialsEventHandler $generateCredentialsEventHandler,
    ) {
    }

    public function __invoke(UserAuthenticatedEvent $event): void
    {
        $this->generateCredentialsEventHandler->handle($event);
    }

    public static function getSubscribedEvents(): array
    {
        return [UserAuthenticatedEvent::class => '__invoke'];
    }
}
