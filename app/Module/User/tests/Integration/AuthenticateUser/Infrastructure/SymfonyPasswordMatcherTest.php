<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\Tests\Integration\AuthenticateUser\Infrastructure;

use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\HashedPassword;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Service\PasswordMatcher;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\ValueObject\PlainPassword;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final class SymfonyPasswordMatcherTest extends KernelTestCase
{
    /** @test */
    public function passwordMatcher(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        /** @var PasswordMatcher $passwordMatcher */
        $passwordMatcher = $container->get(PasswordMatcher::class);
        /** @var PasswordHasherInterface $passwordHasher */
        $passwordHasher = $container->get(PasswordHasherInterface::class);
        $passwordMatcher->match(
            PlainPassword::fromString('Password123!'),
            HashedPassword::fromString($passwordHasher->hash('Password123!'))
        );
        $this->expectNotToPerformAssertions();
    }
}
