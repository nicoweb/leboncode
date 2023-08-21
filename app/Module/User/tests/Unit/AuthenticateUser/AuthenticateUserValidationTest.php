<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\Tests\Unit\AuthenticateUser;

use NicolasLefevre\LeBonCode\Core\Domain\Error\EmailValidationError;
use NicolasLefevre\LeBonCode\Core\Domain\Error\PasswordValidationError;
use NicolasLefevre\LeBonCode\Core\Domain\Error\ValidationError;
use NicolasLefevre\LeBonCode\Core\Domain\Violation\ViolationList;
use NicolasLefevre\LeBonCode\Core\Infrastructure\InMemoryEventDispatcher;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Application\AuthenticateUserCommand;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Application\AuthenticateUserCommandHandler;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Error\InvalidCredentialsError;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Repository\InMemoryAuthenticateUserRepository;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Service\StubPasswordMatcher;
use PHPUnit\Framework\TestCase;

final class AuthenticateUserValidationTest extends TestCase
{
    private AuthenticateUserCommandHandler $handler;

    public function setUp(): void
    {
        $this->handler = new AuthenticateUserCommandHandler(
            new InMemoryAuthenticateUserRepository(),
            new StubPasswordMatcher(),
            new InMemoryEventDispatcher(),
        );
    }

    /** @test */
    public function itShouldThrowErrorWhenEmailFormatIsInvalid(): void
    {
        try {
            $this->handler->__invoke(AuthenticateUserCommand::create('john.doe', 'Password123!'));
            $this->fail('Expected ValidationError was not thrown.');
        } catch (ValidationError $e) {
            $this->assertViolation($e->violations, 1, [[EmailValidationError::MESSAGE => EmailValidationError::PROPERTY_PATH]]);
        }
    }

    /** @test */
    public function itShouldThrowErrorWhenPasswordIsEmpty(): void
    {
        try {
            $this->handler->__invoke(AuthenticateUserCommand::create('john.doe@test.com', ''));
            $this->fail('Expected ValidationError was not thrown.');
        } catch (ValidationError $e) {
            $this->assertViolation($e->violations, 1, [[PasswordValidationError::IS_EMPTY => PasswordValidationError::PROPERTY_PATH]]);
        }
    }

    /** @test */
    public function itShouldThrowErrorWhenEmailDoesNotExist(): void
    {
        try {
            $this->handler->__invoke(AuthenticateUserCommand::create('jane@doe.com', 'pass'));
            $this->fail('Expected ValidationError was not thrown.');
        } catch (ValidationError $e) {
            $this->assertViolation($e->violations, 1, [[InvalidCredentialsError::MESSAGE => InvalidCredentialsError::PROPERTY_PATH]]);
        }
    }

    /** @test */
    public function itShouldThrowErrorWhenPasswordDoesNotMatch(): void
    {
        try {
            $this->handler->__invoke(AuthenticateUserCommand::create('john.doe@test.com', 'pass'));
            $this->fail('Expected ValidationError was not thrown.');
        } catch (ValidationError $e) {
            $this->assertViolation($e->violations, 1, [[InvalidCredentialsError::MESSAGE => InvalidCredentialsError::PROPERTY_PATH]]);
        }
    }

    /**
     * @param array<array<string, string>> $expectedViolations
     */
    private function assertViolation(ViolationList $violationList, int $quantity, array $expectedViolations): void
    {
        $this->assertCount($quantity, $violationList->items);
        $i = 0;
        foreach ($expectedViolations as $violation) {
            $message = array_key_first($violation);
            $propertyPath = $violation[$message];
            $this->assertEquals($message, $violationList->items[$i]->message);
            $this->assertEquals($propertyPath, $violationList->items[$i]->propertyPath);
            ++$i;
        }
    }
}
