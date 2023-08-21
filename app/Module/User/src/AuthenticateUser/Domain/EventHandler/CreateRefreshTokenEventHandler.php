<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\EventHandler;

use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Event\CredentialsGeneratedEvent;

interface CreateRefreshTokenEventHandler
{
    public function handle(CredentialsGeneratedEvent $event): void;
}
