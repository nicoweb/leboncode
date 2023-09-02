<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\Tests\Integration\CreateAdvert\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Entity\Advert;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error\AdvertIdAlreadyExistError;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error\AuthorIdNotExistError;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Repository\CreateAdvertRepository;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject\AuthorId;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject\City;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject\Description;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject\PostalCode;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject\Price;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\ValueObject\Title;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\AdvertId;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\User;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Service\StubUuidGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

final class DoctrineCreateAdvertRepositoryTest extends KernelTestCase
{
    private CreateAdvertRepository $repository;
    private EntityManagerInterface $entityManager;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->repository = $container->get(CreateAdvertRepository::class);

        $this->entityManager = $container
            ->get('doctrine')
            ->getManager()
        ;
    }

    /** @test */
    public function itShouldthrowAnErrorWhenUserIsNotExist(): void
    {
        $this->expectException(AuthorIdNotExistError::class);
        $this->repository->save($this->createAdvert());
    }

    /** @test */
    public function itShouldthrowAnErrorWhenAdvertIdIsAlreadyExist(): void
    {
        $this->saveUser();
        $this->repository->save($this->createAdvert());
        $this->expectException(AdvertIdAlreadyExistError::class);
        $this->repository->save($this->createAdvert());
    }

    private function createAdvert(): Advert
    {
        return new Advert(
            id: AdvertId::fromString(StubUuidGenerator::FIXED_UUID),
            authorId: AuthorId::fromString(StubUuidGenerator::FIXED_UUID),
            title: Title::fromString('title'),
            description: Description::fromString(str_repeat('a', 200)),
            price: Price::fromFloat(10.0),
            postalCode: PostalCode::fromString('75000'),
            city: City::fromString('Paris'),
        );
    }

    private function saveUser(): void
    {
        $user = new User();
        $user->setId(Uuid::fromString(StubUuidGenerator::FIXED_UUID));
        $user->setEmail('john.doe@test.com');
        $user->setFirstname('John');
        $user->setLastname('Doe');
        $user->setPassword('hashed_password');

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
