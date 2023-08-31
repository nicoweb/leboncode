<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Infrastructure\Repository;

use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Entity\Advert;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error\AdvertIdAlreadyExistError;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Error\AuthorIdNotExistError;
use NicolasLefevre\LeBonCode\Advert\CreateAdvert\Domain\Repository\CreateAdvertRepository;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\User;

final class InMemoryCreateAdvertRepository implements CreateAdvertRepository
{
    /**
     * @var array<Advert>
     */
    public array $adverts = [];

    /**
     * @var array<User>
     */
    public array $users = [];

    public function save(Advert $advert): void
    {
        if (!$this->assertHasAuthorId((string) $advert->authorId)) {
            throw new AuthorIdNotExistError();
        }

        if ($this->assertHasAdvertWithId((string) $advert->id)) {
            throw new AdvertIdAlreadyExistError();
        }

        $this->adverts[] = $advert;
    }

    public function assertHasAdvertWithId(string $id): bool
    {
        foreach ($this->adverts as $advert) {
            if ((string) $advert->id === $id) {
                return true;
            }
        }

        return false;
    }

    public function assertHasNotAdvertWithId(string $id): bool
    {
        return false === $this->assertHasAdvertWithId($id);
    }

    public function saveUser(User $user): void
    {
        $this->users[] = $user;
    }

    private function assertHasAuthorId(string $authorId): bool
    {
        foreach ($this->users as $user) {
            if ($user->getId()->toRfc4122() === $authorId) {
                return true;
            }
        }

        return false;
    }
}
