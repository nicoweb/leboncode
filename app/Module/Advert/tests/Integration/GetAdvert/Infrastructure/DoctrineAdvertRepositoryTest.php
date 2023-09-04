<?php

declare(strict_types=1);

namespace Integration\GetAdvert\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Error\AdvertNotFoundError;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Repository\AdvertRepository;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\AdvertId;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\Advert;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Service\StubUuidGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

final class DoctrineAdvertRepositoryTest extends KernelTestCase
{
    private AdvertRepository $repository;
    private EntityManagerInterface $entityManager;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->repository = $container->get(AdvertRepository::class);

        $this->entityManager = $container
            ->get('doctrine')
            ->getManager()
        ;
    }

    /** @test */
    public function itShouldThrowAnErrorWhenAdvertIsNotFound(): void
    {
        $this->expectException(AdvertNotFoundError::class);

        $this->repository->getAdvertById(AdvertId::fromString(StubUuidGenerator::FIXED_UUID));
    }

    /** @test */
    public function itShouldFindAdvert(): void
    {
        $description = str_repeat('description', 100);
        $advert = new Advert();
        $advert->setId(Uuid::fromString(StubUuidGenerator::FIXED_UUID));
        $advert->setTitle('title');
        $advert->setDescription($description);
        $advert->setPrice(100);
        $advert->setPostalCode('75000');
        $advert->setCity('Paris');
        $advert->setDeletedAt(null);

        $this->entityManager->persist($advert);
        $this->entityManager->flush();

        $advert = $this->repository->getAdvertById(AdvertId::fromString(StubUuidGenerator::FIXED_UUID));

        $this->assertSame(StubUuidGenerator::FIXED_UUID, (string) $advert->id);
    }
}
