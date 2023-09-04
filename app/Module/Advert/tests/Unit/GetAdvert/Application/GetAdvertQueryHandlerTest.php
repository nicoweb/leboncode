<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\Tests\Unit\GetAdvert\Application;

use DateTimeImmutable;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Application\GetAdvertQueryHandler;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Entity\Advert;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Error\AdvertDeletedError;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Error\AdvertNotFoundError;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Handler\GetAdvertQuery;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Infrastructure\InMemoryAdvertRepository;
use NicolasLefevre\LeBonCode\Core\Domain\Error\IdValidationError;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\AdvertId;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Service\StubDateProvider;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Service\StubUuidGenerator;
use PHPUnit\Framework\TestCase;

final class GetAdvertQueryHandlerTest extends TestCase
{
    private InMemoryAdvertRepository $repository;
    private GetAdvertQueryHandler $queryHandler;
    private DateTimeImmutable $now;

    public function setUp(): void
    {
        $this->repository = new InMemoryAdvertRepository();
        $this->queryHandler = new GetAdvertQueryHandler($this->repository);
        $this->now = (new StubDateProvider(new DateTimeImmutable(StubDateProvider::NOW)))->now();
    }

    /** @test */
    public function itShouldThrowAnErrorWhenIdIsNotValid(): void
    {
        $this->expectException(IdValidationError::class);

        $this->queryHandler->handle(new GetAdvertQuery('invalid_uuid'));
    }

    /** @test */
    public function itShouldThrowAnErrorWhenAdvertIsNotFound(): void
    {
        $this->expectException(AdvertNotFoundError::class);

        $this->queryHandler->handle(new GetAdvertQuery(StubUuidGenerator::FIXED_UUID));
    }

    /** @test */
    public function itShouldReturnAnAdvertWhenAdvertIsAvailableInTheSystem(): void
    {
        $description = str_repeat('description', 100);
        $this->repository->adverts[StubUuidGenerator::FIXED_UUID] = new Advert(
            AdvertId::fromString(StubUuidGenerator::FIXED_UUID),
            'title',
            $description,
            1000,
            '75000',
            'Paris',
            null,
        );

        $advert = $this->queryHandler->handle(new GetAdvertQuery(StubUuidGenerator::FIXED_UUID));

        $this->assertInstanceOf(Advert::class, $advert);
        $this->assertSame(StubUuidGenerator::FIXED_UUID, (string) $advert->id);
        $this->assertSame('title', $advert->title);
        $this->assertSame($description, $advert->description);
        $this->assertSame(1000, $advert->price);
        $this->assertSame('75000', $advert->postalCode);
        $this->assertSame('Paris', $advert->city);
    }

    /** @test */
    public function itShouldThrowAnErrorWhenAdvertIsDeleted(): void
    {
        $description = str_repeat('description', 100);
        $this->repository->adverts[StubUuidGenerator::FIXED_UUID] = new Advert(
            AdvertId::fromString(StubUuidGenerator::FIXED_UUID),
            'title',
            $description,
            1000,
            '75000',
            'Paris',
            $this->now,
        );

        $this->expectException(AdvertDeletedError::class);

        $this->queryHandler->handle(new GetAdvertQuery(StubUuidGenerator::FIXED_UUID));
    }
}
