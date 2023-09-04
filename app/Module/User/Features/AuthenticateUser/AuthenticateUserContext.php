<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\Features\AuthenticateUser;

use Behat\Behat\Context\Context;
use NicolasLefevre\LeBonCode\Core\Features\Context\CoreContext;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\User;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Service\StubUuidGenerator;
use PHPUnit\Framework\Assert;
use Symfony\Component\Uid\Uuid;

final class AuthenticateUserContext extends CoreContext implements Context
{
    /**
     * @Given /^I have previously registered with valid credentials$/
     */
    public function iHavePreviouslyRegisteredWithValidCredentials(): void
    {
        $this->email = 'jane.doe@test.com';
        $this->password = 'Password123!';

        $user = new User();
        $user->setId(Uuid::fromString(StubUuidGenerator::FIXED_UUID));
        $user->setLastname('Doe');
        $user->setFirstname('Jane');
        $user->setEmail($this->email);
        $user->setPassword($this->passwordHasher->hash($this->password));
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @Given /^I send a POST request to "([^"]*)" with those credentials$/
     */
    public function iSendAPOSTRequestToWithThoseCredentials(string $path): void
    {
        $payload = json_encode([
            'email' => $this->email,
            'password' => $this->password,
        ], JSON_THROW_ON_ERROR);

        $this->browser->request('POST', $path, [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], $payload);

        Assert::assertSame(200, $this->browser->getResponse()->getStatusCode());
    }

    /**
     * @Given /^I should receive an access token$/
     */
    public function iShouldReceiveAnAccessToken(): void
    {
        Assert::assertArrayHasKey('access_token', json_decode($this->browser->getResponse()->getContent(), true));
    }

    /**
     * @Given /^I should receive a refresh token$/
     */
    public function iShouldReceiveARefreshToken(): void
    {
        Assert::assertArrayHasKey('refresh_token', json_decode($this->browser->getResponse()->getContent(), true));
    }
}
