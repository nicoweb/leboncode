<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Application;

use NicolasLefevre\LeBonCode\Core\Domain\CommandHandler;
use NicolasLefevre\LeBonCode\Core\Domain\EventDispatcher;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\Email;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\HashedPassword;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Application\Factory\CredentialsFactory;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Application\Factory\ValidationErrorFactory;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Entity\AuthUser;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Error\EmailNotRegistered;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Error\PasswordDoesNotMatchError;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Event\UserAuthenticatedEvent;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Repository\AuthenticateUserRepository;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Service\PasswordMatcher;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\ValueObject\PlainPassword;

final readonly class AuthenticateUserCommandHandler implements CommandHandler
{
    public function __construct(
        private AuthenticateUserRepository $authenticateUserRepository,
        private PasswordMatcher $passwordMatcher,
        private EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(AuthenticateUserCommand $command): void
    {
        $credentials = CredentialsFactory::fromCommand($command);

        $authUser = $this->findUser($credentials->email);
        $this->assertPasswordsMatch($credentials->plainPassword, $authUser->hashedPassword);

        $this->eventDispatcher->dispatch(
            new UserAuthenticatedEvent($authUser)
        );
    }

    private function findUser(Email $email): AuthUser
    {
        try {
            return $this->authenticateUserRepository->findOneByEmail($email);
        } catch (EmailNotRegistered) {
            throw ValidationErrorFactory::createInvalidCredentialsError();
        }
    }

    private function assertPasswordsMatch(PlainPassword $plainPassword, HashedPassword $hashedPassword): void
    {
        try {
            $this->passwordMatcher->match($plainPassword, $hashedPassword);
        } catch (PasswordDoesNotMatchError) {
            throw ValidationErrorFactory::createInvalidCredentialsError();
        }
    }
}
