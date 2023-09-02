<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Features\Context;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\User;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Service\StubUuidGenerator;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class CoreContext implements Context
{
    protected string $email;
    protected string $password;
    protected string $accessToken;
    protected User $user;

    public function __construct(
        protected readonly EntityManagerInterface $entityManager,
        protected readonly PasswordHasherInterface $passwordHasher,
        protected readonly KernelBrowser $browser,
    ) {
    }

    /**
     * @Then /^the response status code should be (\d+)$/
     */
    public function theResponseStatusCodeShouldBe(string $expectedStatus): void
    {
        $statusCode = $this->browser->getResponse()->getStatusCode();

        Assert::assertSame((int) $expectedStatus, $statusCode);
    }

    /**
     * @Given /^I am already authenticated with valid credentials$/
     */
    public function iAmAlreadyAuthenticatedWithValidCredentials(): void
    {
        $this->email = 'jane.doe@test.com';
        $this->password = 'Password123!';

        $this->saveUser();
        $this->browser->request('POST', '/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'email' => $this->email,
            'password' => $this->password,
        ], JSON_THROW_ON_ERROR));
    }

    protected function saveUser(): void
    {
        $user = new User();
        $user->setId(Uuid::fromString(StubUuidGenerator::FIXED_UUID));
        $user->setLastname('Doe');
        $user->setFirstname('Jane');
        $user->setEmail($this->email);
        $user->setPassword($this->passwordHasher->hash($this->password));
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->user = $user;
    }

    /**
     * @Given /^I have a valid access token$/
     */
    public function iHaveAValidAccessToken(): void
    {
        $tokens = json_decode(
            $this->browser->getResponse()->getContent(),
            true,
            JSON_THROW_ON_ERROR,
            JSON_THROW_ON_ERROR,
        );

        $this->accessToken = $tokens['access_token'];
    }
}
