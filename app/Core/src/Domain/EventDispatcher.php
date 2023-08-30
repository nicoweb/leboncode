<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Domain;

interface EventDispatcher
{
    /**
     * Dispatches an event to all registered listeners.
     *
     * @template T of object
     *
     * @param T $event The event to pass to the event handlers/listeners
     *
     * @return T The passed $event MUST be returned
     */
    public function dispatch(object $event): object;
}
