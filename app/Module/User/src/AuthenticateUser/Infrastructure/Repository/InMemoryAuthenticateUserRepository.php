<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Repository;

use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\Email;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\HashedPassword;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\UserId;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Entity\AuthUser;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Error\EmailNotRegistered;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Repository\AuthenticateUserRepository;

final readonly class InMemoryAuthenticateUserRepository implements AuthenticateUserRepository
{
    /**
     * @var array<string, array{
     *     id: string,
     *     email: string,
     *     password: string,
     *     roles: string[]
     * }>
     */
    public array $users;

    public function __construct()
    {
        $this->users = [
            'john.doe@test.com' => [
                'id' => '45a231b2-dd54-4836-abbf-2da1fcc45ec8',
                'email' => 'john.doe@test.com',
                'password' => 'hashed_password',
                'roles' => ['ROLE_USER'],
            ],
        ];
    }

    public function findOneByEmail(Email $email): AuthUser
    {
        $emailStr = (string) $email;

        if (isset($this->users[$emailStr])) {
            $userData = $this->users[$emailStr];

            return new AuthUser(
                UserId::fromString($userData['id']),
                $email,
                HashedPassword::fromString($userData['password']),
                $userData['roles'],
            );
        }

        throw new EmailNotRegistered();
    }
}
