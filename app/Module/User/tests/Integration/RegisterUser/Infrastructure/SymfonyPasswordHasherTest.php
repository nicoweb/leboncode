<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\Tests\Integration\RegisterUser\Infrastructure;

use NicolasLefevre\LeBonCode\User\RegisterUser\Domain\PasswordHasher\PasswordHasher;
use NicolasLefevre\LeBonCode\User\RegisterUser\Infrastructure\PasswordHasher\SymfonyPasswordHasher;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final class SymfonyPasswordHasherTest extends KernelTestCase
{
    private SymfonyPasswordHasher $hasher;
    private PasswordHasherInterface $symfonyHasher;

    public function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->hasher = $container->get(PasswordHasher::class);
        $this->symfonyHasher = $container->get(PasswordHasherInterface::class);
    }

    /** @test */
    public function itShouldProduceAHashDifferentFromTheOriginalPassword(): void
    {
        $hashedPassword = $this->hasher->hash('password');
        $this->assertNotSame('password', $hashedPassword->value);
    }

    /** @test */
    public function itShouldProduceAValidHashForAGivenPassword(): void
    {
        $hashedPassword = $this->hasher->hash('password');
        $this->assertTrue($this->symfonyHasher->verify($hashedPassword->value, 'password'));
    }

    /** @test */
    public function itShouldProduceAHashThatIsNotValidForAnIncorrectPassword(): void
    {
        $hashedPassword = $this->hasher->hash('password');
        $this->assertFalse($this->symfonyHasher->verify($hashedPassword->value, 'wrongpassword'));
    }
}
