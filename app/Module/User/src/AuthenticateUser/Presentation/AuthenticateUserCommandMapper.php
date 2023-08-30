<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Presentation;

use NicolasLefevre\LeBonCode\User\AuthenticateUser\Application\AuthenticateUserCommand;

final class AuthenticateUserCommandMapper
{
    public static function fromRequest(AuthenticateUserRequest $request): AuthenticateUserCommand
    {
        return AuthenticateUserCommand::create(
            $request->email,
            $request->password,
        );
    }
}
