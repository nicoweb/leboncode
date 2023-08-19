<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Presentation;

use NicolasLefevre\LeBonCode\User\RegisterUser\Application\RegisterUserCommand;

final class RegisterUserCommandMapper
{
    public static function fromRequest(RegisterUserRequest $request): RegisterUserCommand
    {
        return RegisterUserCommand::create(
            $request->id,
            $request->firstname,
            $request->lastname,
            $request->email,
            $request->password,
        );
    }
}
