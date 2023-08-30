<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\EventHandler;

use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Event\UserAuthenticatedEvent;

interface GenerateCredentialsEventHandler
{
    public function handle(UserAuthenticatedEvent $event): void;
}
