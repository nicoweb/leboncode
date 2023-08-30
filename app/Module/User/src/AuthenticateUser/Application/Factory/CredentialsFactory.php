<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Application\Factory;

use NicolasLefevre\LeBonCode\Core\Domain\Error\EmailValidationError;
use NicolasLefevre\LeBonCode\Core\Domain\Error\PasswordValidationError;
use NicolasLefevre\LeBonCode\Core\Domain\Error\ValidationError;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\Email;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Application\AuthenticateUserCommand;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\ValueObject\Credentials;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\ValueObject\PlainPassword;

final readonly class CredentialsFactory
{
    private function __construct(
        public Email $email,
        public PlainPassword $plainPassword,
    ) {
    }

    public static function fromCommand(AuthenticateUserCommand $command): Credentials
    {
        self::validate($command);

        return new Credentials(
            email: Email::fromString($command->email),
            plainPassword: PlainPassword::fromString($command->plainPassword),
        );
    }

    private static function validate(AuthenticateUserCommand $command): void
    {
        $validationError = new ValidationError();

        try {
            Email::validate($command->email);
        } catch (EmailValidationError $e) {
            $validationError->addViolations($e->violations);
        }

        try {
            PlainPassword::validate($command->plainPassword);
        } catch (PasswordValidationError $e) {
            $validationError->addViolations($e->violations);
        }

        if ($validationError->hasViolations()) {
            throw $validationError;
        }
    }
}
