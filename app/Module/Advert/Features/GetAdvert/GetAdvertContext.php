<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\Features\GetAdvert;

use Behat\Behat\Context\Context;
use NicolasLefevre\LeBonCode\Core\Features\Context\CoreContext;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\Advert;
use PHPUnit\Framework\Assert;
use Symfony\Component\Uid\Uuid;

final class GetAdvertContext extends CoreContext implements Context
{
    /**
     * @Given /^an advert with ID "([^"]*)" exists$/
     */
    public function anAdvertWithIDExists(string $advertId): void
    {
        $advert = new Advert();
        $advert->setId(Uuid::fromString($advertId));
        $advert->setTitle('My advert');
        $advert->setDescription('My advert description');
        $advert->setPrice(1000);
        $advert->setPostalCode('75000');
        $advert->setCity('Paris');
        $advert->setUser($this->user);

        $this->entityManager->persist($advert);
        $this->entityManager->flush();
    }

    /**
     * @When /^I send a GET request to "([^"]*)" with the access token in the Bearer header$/
     */
    public function iSendAGETRequestToWithTheAccessTokenInTheBearerHeader(string $path): void
    {
        $this->browser->request('GET', $path, [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer '.$this->accessToken,
        ]);
    }

    /**
     * @Given /^the response should contain the advert details$/
     */
    public function theResponseShouldContainTheAdvertDetails(): void
    {
        $response = json_decode($this->browser->getResponse()->getContent(), true);

        $this->assertAdvertDetails($response);
    }

    private function assertAdvertDetails(array $response): void
    {
        Assert::assertArrayHasKey('id', $response);
        Assert::assertArrayHasKey('title', $response);
        Assert::assertArrayHasKey('description', $response);
        Assert::assertArrayHasKey('price', $response);
        Assert::assertArrayHasKey('postalCode', $response);
        Assert::assertArrayHasKey('city', $response);
    }
}
