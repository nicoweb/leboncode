<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\Tests\Unit\RegisterUser\Application;

use NicolasLefevre\LeBonCode\Core\Domain\Error\ValidationError;
use NicolasLefevre\LeBonCode\Core\Domain\Violation\ViolationList;
use NicolasLefevre\LeBonCode\User\RegisterUser\Application\RegisterUserCommand;
use NicolasLefevre\LeBonCode\User\RegisterUser\Application\RegisterUserCommandHandler;
use NicolasLefevre\LeBonCode\User\RegisterUser\Application\RegisterUserFactory;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error\EmailAlreadyRegisteredValidationError;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error\EmailValidationError;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error\FirstnameValidationError;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error\LastnameValidationError;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error\PasswordValidationError;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error\UserIdAlreadyExistsValidationError;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\Error\UserIdValidationError;
use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\RegisterUserRepository\RegisterUserRepository;
use NicolasLefevre\LeBonCode\User\RegisterUser\Infrastructure\PasswordHasher\InMemoryHasher;
use NicolasLefevre\LeBonCode\User\RegisterUser\Infrastructure\Repository\InMemoryRegisterUserRepository;
use PHPUnit\Framework\TestCase;

final class RegisterUserCommandHandlerTest extends TestCase
{
    private RegisterUserCommandHandler $handler;
    private RegisterUserRepository $repository;

    public function setUp(): void
    {
        $this->repository = new InMemoryRegisterUserRepository();
        $hasher = new InMemoryHasher();
        $this->handler = new RegisterUserCommandHandler(new RegisterUserFactory($hasher), $this->repository);
    }

    /** @test */
    public function itShouldThrowExceptionWhenIdIsNotValid(): void
    {
        try {
            $this->handler->__invoke(RegisterUserCommand::create(
                id: 'invalid-id',
                firstname: 'John',
                lastname: 'Doe',
                email: 'jane.doe@test.com',
                plainPassword: 'Password1234!'
            ));
            $this->fail('Expected ValidationException was not thrown.');
        } catch (ValidationError $e) {
            $this->assertViolation($e->violations, 1, [[UserIdValidationError::MESSAGE => UserIdValidationError::PROPERTY_PATH]]);
        }
    }

    /** @test */
    public function itShouldThrowExceptionWhenEmailIsNotValid(): void
    {
        try {
            $this->handler->__invoke(RegisterUserCommand::create(
                id: '21d7d6c0-30f5-4255-8908-ff4c4728a30b',
                firstname: 'John',
                lastname: 'Doe',
                email: 'invalid-email',
                plainPassword: 'Password1234!',
            ));
            $this->fail('Expected ValidationException was not thrown.');
        } catch (ValidationError $e) {
            $this->assertViolation($e->violations, 1, [[EmailValidationError::MESSAGE => EmailValidationError::PROPERTY_PATH]]);
        }
    }

    /** @test */
    public function itShouldThrowExceptionWhenLastnameIsNotValid(): void
    {
        try {
            $this->handler->__invoke(RegisterUserCommand::create(
                id: '21d7d6c0-30f5-4255-8908-ff4c4728a30b',
                firstname: 'John',
                lastname: 'D',
                email: 'john.doe@test.com',
                plainPassword: 'Password1234!'
            ));
            $this->fail('Expected ValidationException was not thrown.');
        } catch (ValidationError $e) {
            $this->assertViolation($e->violations, 1, [[LastnameValidationError::MESSAGE => LastnameValidationError::PROPERTY_PATH]]);
        }
    }

    /** @test */
    public function itShouldThrowExceptionWhenFirstnameIsNotValid(): void
    {
        try {
            $this->handler->__invoke(RegisterUserCommand::create(
                id: '21d7d6c0-30f5-4255-8908-ff4c4728a30b',
                firstname: 'J',
                lastname: 'Doe',
                email: 'john.doe@test.com',
                plainPassword: 'Password1234!'
            ));
            $this->fail('Expected ValidationException was not thrown.');
        } catch (ValidationError $e) {
            $this->assertViolation($e->violations, 1, [[FirstnameValidationError::MESSAGE => FirstnameValidationError::PROPERTY_PATH]]);
        }
    }

    /** @test */
    public function itShouldThrowExceptionWhenPasswordIsTooShort(): void
    {
        try {
            $this->handler->__invoke(RegisterUserCommand::create(
                id: '21d7d6c0-30f5-4255-8908-ff4c4728a30b',
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@test.com',
                plainPassword: 'aA!1'
            ));
            $this->fail('Expected ValidationException was not thrown.');
        } catch (ValidationError $e) {
            $this->assertViolation($e->violations, 1, [[PasswordValidationError::TOO_SHORT => PasswordValidationError::PROPERTY_PATH]]);
        }
    }

    /** @test */
    public function itShouldThrowExceptionWhenPasswordIsTooLong(): void
    {
        try {
            $this->handler->__invoke(RegisterUserCommand::create(
                id: '21d7d6c0-30f5-4255-8908-ff4c4728a30b',
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@test.com',
                plainPassword: str_repeat('aA1!', 70),
            ));
            $this->fail('Expected ValidationException was not thrown.');
        } catch (ValidationError $e) {
            $this->assertViolation($e->violations, 1, [[PasswordValidationError::TOO_LONG => PasswordValidationError::PROPERTY_PATH]]);
        }
    }

    /** @test */
    public function itShouldThrowExceptionWhenPasswordHasNoUppercase(): void
    {
        try {
            $this->handler->__invoke(RegisterUserCommand::create(
                id: '21d7d6c0-30f5-4255-8908-ff4c4728a30b',
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@test.com',
                plainPassword: 'password1234!',
            ));
            $this->fail('Expected ValidationException was not thrown.');
        } catch (ValidationError $e) {
            $this->assertViolation($e->violations, 1, [[PasswordValidationError::NO_UPPERCASE => PasswordValidationError::PROPERTY_PATH]]);
        }
    }

    /** @test */
    public function itShouldThrowExceptionWhenPasswordHasNoLowercase(): void
    {
        try {
            $this->handler->__invoke(RegisterUserCommand::create(
                id: '21d7d6c0-30f5-4255-8908-ff4c4728a30b',
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@test.com',
                plainPassword: 'PASSWORD1234!',
            ));
            $this->fail('Expected ValidationException was not thrown.');
        } catch (ValidationError $e) {
            $this->assertViolation($e->violations, 1, [[PasswordValidationError::NO_LOWERCASE => PasswordValidationError::PROPERTY_PATH]]);
        }
    }

    /** @test */
    public function itShouldThrowExceptionWhenPasswordHasNoNumber(): void
    {
        try {
            $this->handler->__invoke(RegisterUserCommand::create(
                id: '21d7d6c0-30f5-4255-8908-ff4c4728a30b',
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@test.com',
                plainPassword: 'PASSWORDab!',
            ));
            $this->fail('Expected ValidationException was not thrown.');
        } catch (ValidationError $e) {
            $this->assertViolation($e->violations, 1, [[PasswordValidationError::NO_DIGIT => PasswordValidationError::PROPERTY_PATH]]);
        }
    }

    /** @test */
    public function itShouldThrowExceptionWhenPasswordHasNoScpecialCharacter(): void
    {
        try {
            $this->handler->__invoke(RegisterUserCommand::create(
                id: '21d7d6c0-30f5-4255-8908-ff4c4728a30b',
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@test.com',
                plainPassword: 'PASSWORDab1',
            ));
            $this->fail('Expected ValidationException was not thrown.');
        } catch (ValidationError $e) {
            $this->assertViolation($e->violations, 1, [[PasswordValidationError::NO_SPECIAL_CHARACTER => PasswordValidationError::PROPERTY_PATH]]);
        }
    }

    /** @test */
    public function itShouldHave5ErrorsOnEmailPasswordFirsnameAndLastname(): void
    {
        try {
            $this->handler->__invoke(RegisterUserCommand::create(
                id: '21d7d6c0-30f5-4255-8908-ff4c4728a30b',
                firstname: 'J',
                lastname: 'D',
                email: 'john.doetest.com',
                plainPassword: 'PASSWORD1',
            ));
            $this->fail('Expected ValidationException was not thrown.');
        } catch (ValidationError $e) {
            $this->assertViolation($e->violations, 5, [
                [FirstnameValidationError::MESSAGE => FirstnameValidationError::PROPERTY_PATH],
                [LastnameValidationError::MESSAGE => LastnameValidationError::PROPERTY_PATH],
                [EmailValidationError::MESSAGE => EmailValidationError::PROPERTY_PATH],
                [PasswordValidationError::NO_LOWERCASE => PasswordValidationError::PROPERTY_PATH],
                [PasswordValidationError::NO_SPECIAL_CHARACTER => PasswordValidationError::PROPERTY_PATH],
            ]);
        }
    }

    public function itShouldRegisterUser(): void
    {
        $this->handler->__invoke(
            RegisterUserCommand::create(
                id: '21d7d6c0-30f5-4255-8908-ff4c4728a30b',
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@test.com',
                plainPassword: 'Password123!',
            )
        );
        if (!$this->repository instanceof InMemoryRegisterUserRepository) {
            $this->fail('Expected InMemoryUserRepository was not injected.');
        }

        $this->assertCount(1, $this->repository->users);

        /** @var InMemoryRegisterUserRepository $inMemoryRepo */
        $inMemoryRepo = $this->repository;

        if (!$user = reset($inMemoryRepo->users)) {
            $this->fail('Expected user was not created.');
        }

        $this->assertEquals('21d7d6c0-30f5-4255-8908-ff4c4728a30b', $user->id);
        $this->assertEquals('John', $user->firstname);
        $this->assertEquals('Doe', $user->lastname);
        $this->assertEquals('Hashed_Password123!', $user->password);
        $this->assertEquals('john.doe@test.com', $user->email);
    }

    /** @test */
    public function itShouldThrowExceptionWhenUserIdIsAlreadyTaken(): void
    {
        $this->expectException(UserIdAlreadyExistsValidationError::class);
        $this->handler->__invoke(
            RegisterUserCommand::create(
                id: '6a00edd7-5da1-43fa-9e84-3ef756f1d079',
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@test.com',
                plainPassword: 'Password123!',
            )
        );
    }

    /** @test */
    public function itShouldThrowExceptionWhenEmailIsAlreadyRegistered(): void
    {
        $this->expectException(EmailAlreadyRegisteredValidationError::class);
        $this->handler->__invoke(
            RegisterUserCommand::create(
                id: '6a00edd7-5da1-43fa-9e84-3ef756f1d082',
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe1@test.com',
                plainPassword: 'Password123!',
            )
        );
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
