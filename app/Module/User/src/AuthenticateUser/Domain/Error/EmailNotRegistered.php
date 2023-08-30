<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Error;

use DomainException;

final class EmailNotRegistered extends DomainException
{
}
