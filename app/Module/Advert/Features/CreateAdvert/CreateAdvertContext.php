<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\Features\CreateAdvert;

use Behat\Behat\Context\Context;
use NicolasLefevre\LeBonCode\Core\Features\Context\CoreContext;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Service\StubUuidGenerator;
use Symfony\Component\Uid\Uuid;

final class CreateAdvertContext extends CoreContext implements Context
{
    /**
     * @When /^I send a POST request to "([^"]*)" with my advert details and the access token in the Bearer header$/
     */
    public function iSendAPOSTRequestToWithMyAdvertDetailsAndTheAccessTokenInTheBearerHeader(string $path): void
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
}
