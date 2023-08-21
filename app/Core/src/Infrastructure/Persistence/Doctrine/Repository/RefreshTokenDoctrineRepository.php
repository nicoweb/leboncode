<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\RefreshToken;

/**
 * @template-extends ServiceEntityRepository<RefreshToken>
 */
final class RefreshTokenDoctrineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefreshToken::class);
    }
}
