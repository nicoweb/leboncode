<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\Tests\Integration\AuthenticateUser\Infrastructure;

use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Service\TokenHasher;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class SymfonyTokenHasherTest extends KernelTestCase
{
    /** @test */
    public function tokenHasher(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        /** @var TokenHasher $tokenHasher */
        $tokenHasher = $container->get(TokenHasher::class);
        $tokenHasher->hash('token');

        $this->expectNotToPerformAssertions();
    }
}
