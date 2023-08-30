<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\Tests\Unit\AuthenticateUser;

use DateTimeImmutable;
use NicolasLefevre\LeBonCode\Core\Infrastructure\InMemoryEventDispatcher;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Service\StubDateProvider;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Service\StubUuidGenerator;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Application\AuthenticateUserCommand;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Application\AuthenticateUserCommandHandler;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Application\AuthenticateUserResult;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Application\EventHandler\CreateRefreshTokenEventHandler;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Application\EventHandler\GenerateCredentialsEventHandler;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Application\EventHandler\PersistRefreshTokenEventHandler;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Event\CredentialsGeneratedEvent;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Event\RefreshTokenCreatedEvent;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Domain\Event\UserAuthenticatedEvent;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Listener\CreateRefreshTokenListener;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Listener\GenerateCredentialsListener;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Listener\StoreRefreshTokenListener;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Repository\InMemoryAuthenticateUserRepository;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Repository\InMemoryRefreshTokenRepository;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Service\StubAccessTokenGenerator;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Service\StubPasswordMatcher;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Infrastructure\Service\StubTokenHasher;
use PHPUnit\Framework\TestCase;

final class AuthenticateUserUseCaseTest extends TestCase
{
    private const NOW = '2023-01-01T00:00:00+00:00';
    private AuthenticateUserCommandHandler $handler;
    private InMemoryAuthenticateUserRepository $repository;
    private AuthenticateUserResult $result;
    private InMemoryRefreshTokenRepository $refreshTokenRepository;

    public function setUp(): void
    {
        $now = new DateTimeImmutable(self::NOW);
        $dateProvider = new StubDateProvider($now);
        $idGenerator = new StubUuidGenerator();
        $tokenHasher = new StubTokenHasher();
        $this->result = new AuthenticateUserResult();
        $eventDispatcher = new InMemoryEventDispatcher();
        $this->refreshTokenRepository = new InMemoryRefreshTokenRepository();
        $refreshTokenExpiration = '86400';
        $listener = new GenerateCredentialsListener(
            new GenerateCredentialsEventHandler(
                $idGenerator,
                new StubAccessTokenGenerator(),
                $eventDispatcher
            ),
        );

        $eventDispatcher->addListener(UserAuthenticatedEvent::class, $listener);

        $createRefreshTokenListener = new CreateRefreshTokenListener(
            new CreateRefreshTokenEventHandler(
                $tokenHasher,
                $idGenerator,
                $dateProvider,
                $refreshTokenExpiration,
                $eventDispatcher,
            )
        );

        $eventDispatcher->addListener(CredentialsGeneratedEvent::class, $createRefreshTokenListener);

        $storeRefreshTokenListener = new StoreRefreshTokenListener(
            new PersistRefreshTokenEventHandler(
                $this->refreshTokenRepository,
                $this->result
            )
        );

        $eventDispatcher->addListener(RefreshTokenCreatedEvent::class, $storeRefreshTokenListener);

        $this->repository = new InMemoryAuthenticateUserRepository();
        $this->handler = new AuthenticateUserCommandHandler(
            $this->repository,
            new StubPasswordMatcher(),
            $eventDispatcher,
        );
    }

    /** @test */
    public function itShouldGenerateExpectedTokensWhenCredentialsAreValid(): void
    {
        $this->handler->__invoke(AuthenticateUserCommand::create('john.doe@test.com', 'password'));

        $this->assertSame(StubUuidGenerator::FIXED_UUID, $this->result->getRefreshToken());
        $this->assertSame(StubAccessTokenGenerator::ACCESS_TOKEN, $this->result->getAccessToken());
    }

    /** @test */
    public function itShouldStoreRefreshTokenWhenCredentialsAreValid(): void
    {
        $this->handler->__invoke(AuthenticateUserCommand::create('john.doe@test.com', 'password'));

        $refreshTokens = $this->refreshTokenRepository->findAll();
        $this->assertCount(1, $refreshTokens);
        $refreshToken = $refreshTokens[StubUuidGenerator::FIXED_UUID];
        $this->assertSame('hashed_'.StubUuidGenerator::FIXED_UUID, (string) $refreshToken->token);
        $this->assertSame('2023-01-01T00:00:00+00:00', $refreshToken->createdAt->format(DateTimeImmutable::ATOM));
        $this->assertSame('2023-01-02T00:00:00+00:00', $refreshToken->expiredAt->format(DateTimeImmutable::ATOM));
    }
}
