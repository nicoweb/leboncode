<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\Features\RegisterUser;

use Behat\Behat\Context\Context;
use NicolasLefevre\LeBonCode\Core\Features\Context\CoreContext;
use PHPUnit\Framework\Assert;

final class RegisterUserContext extends CoreContext implements Context
{
    /**
     * @Given /^I send a POST request to "([^"]*)" with valid registration data$/
     */
    public function iSendAPOSTRequestToWithValidRegistrationData(string $path): void
    {
        $payload = json_encode([
            'id' => 'e3b8c1e0-8e9a-4a1a-9a9a-8b0f8a5e5c0e',
            'email' => 'jane.doe@example.com',
            'password' => 'Password123!',
            'firstname' => 'Jane',
            'lastname' => 'Doe',
        ], JSON_THROW_ON_ERROR);

        $this->browser->request('POST', $path, [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], $payload);
    }

    /**
     * @Then /^the response status code should be (\d+)$/
     */
    public function theResponseStatusCodeShouldBe(string $expectedStatus): void
    {
        $statusCode = $this->browser->getResponse()->getStatusCode();

        Assert::assertSame((int) $expectedStatus, $statusCode);
    }
}
