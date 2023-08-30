<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\EventHandler;

use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Event\RefreshTokenCreatedEvent;

interface PersistRefreshTokenEventHandler
{
    public function handle(RefreshTokenCreatedEvent $event): void;
}
