<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Infrastructure\Repository;

use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Entity\RegisterUser;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error\EmailAlreadyRegisteredValidationError;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error\UserIdAlreadyExistsValidationError;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\RegisterUserRepository\RegisterUserRepository;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject\Email;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject\Firstname;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject\HashedPassword;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject\Lastname;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject\UserId;

final class InMemoryRegisterUserRepository implements RegisterUserRepository
{
    /**
     * @var RegisterUser[]
     */
    public array $users = [];

    public function __construct(
    ) {
        $this->users = [
            new RegisterUser(
                id: UserId::fromString('6a00edd7-5da1-43fa-9e84-3ef756f1d079'),
                firstname: Firstname::fromString('John'),
                lastname: Lastname::fromString('Doe'),
                email: Email::fromString('john.doe1@test.com'),
                password: HashedPassword::fromString('Hashed_Password123!'),
            ),
        ];
    }

    public function save(RegisterUser $registerUser): void
    {
        foreach ($this->users as $user) {
            if ($user->id->equals($registerUser->id)) {
                throw UserIdAlreadyExistsValidationError::create()->withViolation();
            }

            if ($user->email->equals($registerUser->email)) {
                throw EmailAlreadyRegisteredValidationError::create()->withViolation();
            }
        }

        $this->users[] = $registerUser;
    }
}
