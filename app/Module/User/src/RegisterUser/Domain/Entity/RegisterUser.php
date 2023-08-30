<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Entity;

use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\Email;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\HashedPassword;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\UserId;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject\Firstname;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\ValueObject\Lastname;

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
