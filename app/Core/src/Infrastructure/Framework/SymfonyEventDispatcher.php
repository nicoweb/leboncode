<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Infrastructure\Framework;

use NicolasLefevre\LeBonCode\Core\Domain\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final readonly class SymfonyEventDispatcher implements EventDispatcher
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function dispatch(object $event): object
    {
        return $this->eventDispatcher->dispatch($event);
    }
}
