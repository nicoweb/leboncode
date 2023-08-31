<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\Tests\Unit\CreateAdvert\Application;

use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Application\CreateAdvertCommand;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Application\CreateAdvertCommandHandler;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error\AdvertDescriptionTooShortValidationError;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error\AdvertIdAlreadyExistError;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error\AdvertTitleTooLongValidationError;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error\AdvertTitleTooShortValidationError;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error\AuthorIdNotExistError;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error\CityNameTooShortValidationError;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error\InvalidFrenchPostalCodeValidationError;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error\NonPositivePriceValidationError;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Infrastructure\Repository\InMemoryCreateAdvertRepository;
use NicolasLefevre\LeBonCode\Core\Domain\Error\IdValidationError;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\User;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Service\StubUuidGenerator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class CreateAdvertCommandHandlerTest extends TestCase
{
    private CreateAdvertCommandHandler $handler;
    private InMemoryCreateAdvertRepository $repository;
    private string $id;
    public string $validTitle = 'title';
    public string $validDescription = <<<EOT
Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
Mattis rhoncus urna neque viverra. 
Diam phasellus vestibulum lorem sed risus ultricies.
EOT;

    public function setUp(): void
    {
        $this->repository = new InMemoryCreateAdvertRepository();
        $this->handler = new CreateAdvertCommandHandler($this->repository);
        $this->id = (new StubUuidGenerator())->generate();
    }

    /** @test */
    public function itShouldThrowAnErrorWhenIdIsNotValid(): void
    {
        $command = new CreateAdvertCommand('invalid-id', 'a', $this->validDescription, 10.0, '75000', 'Paris', $this->id);

        $this->expectException(IdValidationError::class);

        $this->handler->__invoke($command);
    }

    /** @test */
    public function itShouldThrowAnErrorWhenTitleIsTooShort(): void
    {
        $command = new CreateAdvertCommand($this->id, 'a', $this->validDescription, 10.0, '75000', 'Paris', $this->id);

        $this->expectException(AdvertTitleTooShortValidationError::class);

        $this->handler->__invoke($command);
    }

    /** @test */
    public function itShouldThrowAnErrorWhenTitleIsTooLong(): void
    {
        $titleTooLong = str_repeat('a', 256);
        $command = new CreateAdvertCommand($this->id, $titleTooLong, $this->validDescription, 10.0, '75000', 'Paris', $this->id);

        $this->expectException(AdvertTitleTooLongValidationError::class);

        $this->handler->__invoke($command);
    }

    /** @test */
    public function itShouldThrowAnErrorWhenDescriptionIsTooShort(): void
    {
        $command = new CreateAdvertCommand($this->id, $this->validTitle, 'desc', 10.0, '75000', 'Paris', $this->id);
        $this->expectException(AdvertDescriptionTooShortValidationError::class);

        $this->handler->__invoke($command);
    }

    /** @test */
    public function itShouldThrowAnErrorWhenPriceIsNegative(): void
    {
        $command = new CreateAdvertCommand($this->id, $this->validTitle, $this->validDescription, -10.0, '75000', 'Paris', $this->id);

        $this->expectException(NonPositivePriceValidationError::class);

        $this->handler->__invoke($command);
    }

    /** @test */
    public function itShouldThrowAnErrorWhenPriceIsEqualToZero(): void
    {
        $command = new CreateAdvertCommand($this->id, $this->validTitle, $this->validDescription, 0.0, '75000', 'Paris', $this->id);

        $this->expectException(NonPositivePriceValidationError::class);

        $this->handler->__invoke($command);
    }

    /** @test */
    public function itShouldThrowAnErrorWhenPostalCodeIsNotInFrenchPostalCode(): void
    {
        $command = new CreateAdvertCommand($this->id, $this->validTitle, $this->validDescription, 10.0, '12345', 'Paris', $this->id);
        $this->expectException(InvalidFrenchPostalCodeValidationError::class);

        $this->handler->__invoke($command);
    }

    /** @test */
    public function itShouldThrowAnErrorWhenCityIsNotValid(): void
    {
        $command = new CreateAdvertCommand($this->id, $this->validTitle, $this->validDescription, 10.0, '75000', 'P', $this->id);
        $this->expectException(CityNameTooShortValidationError::class);

        $this->handler->__invoke($command);
    }

    /** @test */
    public function itShouldThrowAnErrorWhenAuthorIdIsNotValid(): void
    {
        $command = new CreateAdvertCommand($this->id, $this->validTitle, $this->validDescription, 10.0, '75000', 'P', 'invalid-author-id');
        $this->expectException(IdValidationError::class);

        $this->handler->__invoke($command);
    }

    /** @test */
    public function itShouldPersistANewAdvert(): void
    {
        $this->assertAdvertNotExist($this->id);
        $user = new User();
        $user->setId(Uuid::fromString($this->id));

        $this->repository->saveUser($user);

        $command = new CreateAdvertCommand($this->id, $this->validTitle, $this->validDescription, 10.0, '75000', 'Paris', $this->id);
        $this->handler->__invoke($command);

        $this->assertAdvertExist($this->id);
    }

    /** @test */
    public function itShouldThrowAnErrorWhenAuthorIdNotExist(): void
    {
        $this->assertAdvertNotExist($this->id);

        $command = new CreateAdvertCommand($this->id, $this->validTitle, $this->validDescription, 10.0, '75000', 'Paris', $this->id);

        $this->expectException(AuthorIdNotExistError::class);

        $this->handler->__invoke($command);
    }

    /** @test */
    public function itShouldThrowAnErrorWhenAdvertIdIsAlreadyPersisted(): void
    {
        $command = new CreateAdvertCommand($this->id, $this->validTitle, $this->validDescription, 10.0, '75000', 'Paris', $this->id);
        $user = new User();
        $user->setId(Uuid::fromString($this->id));

        $this->repository->saveUser($user);
        $this->handler->__invoke($command);
        $this->assertAdvertExist($this->id);

        $this->expectException(AdvertIdAlreadyExistError::class);

        $this->handler->__invoke($command);
    }

    private function assertAdvertNotExist(string $id): void
    {
        $this->assertFalse($this->repository->assertHasAdvertWithId($id));
    }

    private function assertAdvertExist(string $id): void
    {
        $this->assertTrue($this->repository->assertHasAdvertWithId($id));
    }
}
