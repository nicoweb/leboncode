<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\Features\CreateAdvert;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use NicolasLefevre\LeBonCode\Core\Features\Context\CoreContext;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\User;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Service\StubUuidGenerator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

final class CreateAdvertContext extends CoreContext implements Context
{
    private string $email;
    private string $password;
    private string $accessToken;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PasswordHasherInterface $passwordHasher,
        KernelBrowser $browser
    ) {
        parent::__construct($browser);
    }

    /**
     * @Given /^I am already authenticated with valid credentials$/
     */
    public function iAmAlreadyAuthenticatedWithValidCredentials()
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

    /**
     * @Given /^I have a valid access token$/
     */
    public function iHaveAValidAccessToken()
    {
        $tokens = json_decode(
            $this->browser->getResponse()->getContent(),
            true,
            JSON_THROW_ON_ERROR,
        );

        $this->accessToken = $tokens['access_token'];
    }

    /**
     * @When /^I send a POST request to "([^"]*)" with my advert details and the access token in the Bearer header$/
     */
    public function iSendAPOSTRequestToWithMyAdvertDetailsAndTheAccessTokenInTheBearerHeader(string $path)
    {
        $this->browser->request('POST', $path, [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer '.$this->accessToken,
        ], json_encode([
            'id' => Uuid::fromString(StubUuidGenerator::FIXED_UUID)->toRfc4122(),
            'title' => 'My advert',
            'description' => str_repeat('a', 200),
            'price' => 10.0,
            'postalCode' => '75000',
            'city' => 'Paris',
        ], JSON_THROW_ON_ERROR));
    }

    private function saveUser(): void
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
    }
}
