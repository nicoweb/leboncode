<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Application;

use NicolasLefevre\LeBonCode\Core\Domain\Error\EmailValidationError;
use NicolasLefevre\LeBonCode\Core\Domain\Error\IdValidationError;
use NicolasLefevre\LeBonCode\Core\Domain\Error\PasswordValidationError;
use NicolasLefevre\LeBonCode\Core\Domain\Error\ValidationError;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\Email;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\UserId;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Entity\RegisterUser;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error\FirstnameValidationError;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error\LastnameValidationError;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\PasswordHasher\PasswordHasher;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject\Firstname;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject\Lastname;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject\PlainPassword;

final class RegisterUserFactory
{
    private ValidationError $validationError;

    public function __construct(
        private readonly PasswordHasher $passwordHasher,
    ) {
    }

    public function createFromCommand(RegisterUserCommand $command): RegisterUser
    {
        $this->validateCommand($command);

        return new RegisterUser(
            UserId::fromString($command->id),
            Firstname::fromString($command->firstname),
            Lastname::fromString($command->lastname),
            Email::fromString($command->email),
            $this->passwordHasher->hash($command->plainPassword),
        );
    }

    private function validateCommand(RegisterUserCommand $command): void
    {
        $this->validationError = new ValidationError();

        $this->validateUserId($command->id);
        $this->validateFirstname($command->firstname);
        $this->validateLastname($command->lastname);
        $this->validateEmail($command->email);
        $this->validatePlainPassword($command->plainPassword);

        if ($this->validationError->hasViolations()) {
            throw $this->validationError;
        }
    }

    private function validateUserId(string $id): void
    {
        try {
            UserId::validate($id);
        } catch (IdValidationError $e) {
            $this->validationError->addViolations($e->violations);
        }
    }

    private function validateFirstname(string $firstname): void
    {
        try {
            Firstname::validate($firstname);
        } catch (FirstnameValidationError $e) {
            $this->validationError->addViolations($e->violations);
        }
    }

    private function validateLastname(string $lastname): void
    {
        try {
            Lastname::validate($lastname);
        } catch (LastnameValidationError $e) {
            $this->validationError->addViolations($e->violations);
        }
    }

    private function validateEmail(string $email): void
    {
        try {
            Email::validate($email);
        } catch (EmailValidationError $e) {
            $this->validationError->addViolations($e->violations);
        }
    }

    private function validatePlainPassword(string $plainPassword): void
    {
        try {
            PlainPassword::validate($plainPassword);
        } catch (PasswordValidationError $e) {
            $this->validationError->addViolations($e->violations);
        }
    }
}
