<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Infrastructure;

use NicolasLefevre\LeBonCode\Core\Domain\EventDispatcher;

final class InMemoryEventDispatcher implements EventDispatcher
{
    /**
     * @var array<string, array<callable>>
     */
    private array $listeners = [];

    public function dispatch(object $event): object
    {
        $eventName = $event::class;
        if (isset($this->listeners[$eventName])) {
            foreach ($this->listeners[$eventName] as $listener) {
                $listener($event);
            }
        }

        return $event;
    }

    public function addListener(string $eventName, callable $listener): void
    {
        if (!isset($this->listeners[$eventName])) {
            $this->listeners[$eventName] = [];
        }
        $this->listeners[$eventName][] = $listener;
    }
}
