<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Application;

use NicolasLefevre\LeBonCode\Core\Domain\CommandHandler;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\RegisterUserRepository\RegisterUserRepository;

final readonly class RegisterUserCommandHandler implements CommandHandler
{
    public function __construct(
        private RegisterUserFactory $registerUserFactory,
        private RegisterUserRepository $registerUserRepository,
    ) {
    }

    public function __invoke(RegisterUserCommand $command): void
    {
        $registerUser = $this->registerUserFactory->createFromCommand($command);

        $this->registerUserRepository->save($registerUser);
    }
}
