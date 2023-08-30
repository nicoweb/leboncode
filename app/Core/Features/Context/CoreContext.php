<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Features\Context;

use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class CoreContext implements Context
{
    public function __construct(
        protected KernelBrowser $browser,
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
}
