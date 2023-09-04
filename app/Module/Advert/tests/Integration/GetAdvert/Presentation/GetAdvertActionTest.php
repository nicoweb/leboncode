<?php

declare(strict_types=1);

namespace Integration\GetAdvert\Presentation;

use NicolasLefevre\LeBonCode\Advert\GetAdvert\Application\StubQueryHandler;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Handler\QueryHandler;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Service\StubUuidGenerator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class GetAdvertActionTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();
        $stubQueryHandler = new StubQueryHandler();
        $authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $authorizationChecker->method('isGranted')->willReturn(true);
        $container->set(QueryHandler::class, $stubQueryHandler);
        $container->set(AuthorizationCheckerInterface::class, $authorizationChecker);
    }

    /** @test */
    public function itShouldThrowAnErrorWhenIdIsNotValid(): void
    {
        $this->client->request('GET', '/adverts/invalid-id');

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'code' => 422,
                'message' => 'Validation error',
                'violations' => [
                    [
                        'field' => 'id',
                        'error' => 'invalid',
                    ],
                ],
            ], JSON_THROW_ON_ERROR),
            $this->getContent()
        );
    }

    /** @test */
    public function itShouldThrowAnErrorWhenAdvertNotFound(): void
    {
        $this->client->request('GET', '/adverts/not-found-id');

        $this->assertResponseStatusCodeSame(404);
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'code' => 404,
                'message' => 'advert_not_found',
            ], JSON_THROW_ON_ERROR),
            $this->getContent()
        );
    }

    /** @test */
    public function itShouldThrowAnErrorWhenAdvertIsDeleted(): void
    {
        $this->client->request('GET', '/adverts/deleted-id');

        $this->assertResponseStatusCodeSame(404);
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'code' => 404,
                'message' => 'advert_not_found',
            ], JSON_THROW_ON_ERROR),
            $this->getContent()
        );
    }

    /** @test */
    public function itShouldReturnAdvert(): void
    {
        $this->client->request('GET', '/adverts/'.StubUuidGenerator::FIXED_UUID);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'city' => 'paris',
                'description' => 'description',
                'id' => '123e4567-e89b-12d3-a456-426614174000',
                'postalCode' => '75000',
                'price' => 10,
                'title' => 'title',
            ], JSON_THROW_ON_ERROR),
            $this->getContent()
        );
    }

    private function getContent(): string
    {
        $content = $this->client->getResponse()->getContent();

        if (false === $content) {
            $this->fail('Response content is empty');
        }

        return $content;
    }
}
