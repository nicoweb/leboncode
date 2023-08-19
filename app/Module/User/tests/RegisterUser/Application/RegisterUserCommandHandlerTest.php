<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\Tests\RegisterUser\Application;

use PHPUnit\Framework\TestCase;

final class RegisterUserCommandHandlerTest extends TestCase
{
    /** @test */
    public function itShouldLoginAUser(): void
    {
        $this->assertSame(1, 1);
    }
}
