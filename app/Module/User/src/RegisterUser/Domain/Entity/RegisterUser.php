<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Entity;

use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject\Email;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject\Firstname;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject\HashedPassword;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject\Lastname;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject\UserId;

final readonly class RegisterUser
{
    public function __construct(
        public UserId $id,
        public Firstname $firstname,
        public Lastname $lastname,
        public Email $email,
        public HashedPassword $password,
    ) {
    }
}
