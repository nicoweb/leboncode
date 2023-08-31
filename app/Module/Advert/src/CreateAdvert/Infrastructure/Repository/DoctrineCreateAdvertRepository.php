<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Infrastructure\Repository;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Entity\Advert;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error\AdvertIdAlreadyExistError;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error\AuthorIdNotExistError;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Repository\CreateAdvertRepository;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Infrastructure\AdvertORMMapper;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\User;

final readonly class DoctrineCreateAdvertRepository implements CreateAdvertRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function save(Advert $advert): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => (string) $advert->authorId]);

        if (!$user instanceof User) {
            throw new AuthorIdNotExistError();
        }

        $advertORM = AdvertORMMapper::mapFromDomain($advert, $user);

        try {
            $this->entityManager->persist($advertORM);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException) {
            throw new AdvertIdAlreadyExistError();
        }
    }
}
