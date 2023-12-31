<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\Email;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\User;

/**
 * @template-extends ServiceEntityRepository<User>
 */
final class UserDoctrineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function isUserExists(Email $email): bool
    {
        $user = $this->findOneBy(['email' => (string) $email]);

        return $user instanceof User;
    }
}
