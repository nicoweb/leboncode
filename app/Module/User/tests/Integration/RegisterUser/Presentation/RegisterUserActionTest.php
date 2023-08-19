<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\Tests\Integration\RegisterUser\Presentation;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class RegisterUserActionTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    /** @test */
    public function itShouldHandleCorrectlyTheViolations(): void
    {
        $this->client->request('POST', '/users', [
            'id' => 'invalid_id',
            'email' => 'invalid_email',
            'password' => 'pass',
            'firstname' => 'j',
            'lastname' => 'd',
        ]);

        $this->assertResponseStatusCodeSame(422);

        $content = $this->client->getResponse()->getContent();

        if (false === $content) {
            throw new RuntimeException('Response content is empty');
        }

        $expectedViolations = <<<JSON
{
    "code": 422,
    "message": "Validation error",
    "violations": [
        {
            "error": "invalid",
            "field": "id"
        },
        {
            "error": "too_short",
            "field": "firstname"
        },
        {
            "error": "too_short",
            "field": "lastname"
        },
        {
            "error": "not_valid",
            "field": "email"
        },
        {
            "error": "too_short",
            "field": "password"
        },
        {
            "error": "no_uppercase",
            "field": "password"
        },
        {
            "error": "no_digit",
            "field": "password"
        },
        {
            "error": "no_special_character",
            "field": "password"
        }
    ]
}
JSON;

        $this->assertJsonStringEqualsJsonString(
            $expectedViolations,
            $content,
        );
    }

    /** @test */
    public function itShouldRegisterANewUser(): void
    {
        $this->client->request('POST', '/users', [
            'id' => 'c68b19dc-3ff6-41b2-bf2c-78e213c8cfa4',
            'email' => 'john.doe@test.com',
            'password' => 'Password123!',
            'firstname' => 'John',
            'lastname' => 'Doe',
        ]);

        $this->assertResponseStatusCodeSame(204);
    }
}
