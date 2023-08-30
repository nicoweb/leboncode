<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\Tests\Integration\AuthenticateUser\Infrastructure;

use DateTimeImmutable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use NicolasLefevre\LeBonCode\Core\Domain\Service\DateProvider;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\Email;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\HashedPassword;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\UserId;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Service\StubDateProvider;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Entity\AuthUser;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Service\AccessTokenGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class FirebaseJwtAccessTokenGeneratorTest extends KernelTestCase
{
    /** @test */
    public function itShouldCreateRightAccessToken(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $stubDateProvider = new StubDateProvider(new DateTimeImmutable());

        $container->set(DateProvider::class, $stubDateProvider);

        /** @var AccessTokenGenerator $accessTokenGenerator */
        $accessTokenGenerator = $container->get(AccessTokenGenerator::class);

        $accessToken = $accessTokenGenerator->generate(
            new AuthUser(
                UserId::fromString('45a231b2-dd54-4836-abbf-2da1fcc45ec8'),
                Email::fromString('john.doe@test.com'),
                HashedPassword::fromString('hash_password'),
                ['ROLE_USER'],
            )
        );

        $expectedJwt = JWT::decode(
            jwt: $accessToken,
            keyOrKeyArray: new Key($_ENV['JWT_SECRET_KEY'], $_ENV['JWT_ALGORITHM']),
        );

        $this->assertSame(
            [
                'subject' => '45a231b2-dd54-4836-abbf-2da1fcc45ec8',
                'roles' => ['ROLE_USER'],
                'exp' => $stubDateProvider->now()->modify(sprintf('+%d seconds', $_ENV['JWT_ACCESS_TOKEN_EXPIRATION']))->getTimestamp(),
                'iat' => $stubDateProvider->now()->getTimestamp(),
                'email' => 'john.doe@test.com',
            ],
            (array) $expectedJwt,
        );
    }
}
