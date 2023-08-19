<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Features\Context;

use Behat\Behat\Context\Context;
use RuntimeException;
use Symfony\Component\HttpKernel\KernelInterface;

final readonly class KernelContext implements Context
{
    public function __construct(
        private KernelInterface $kernel
    ) {
    }

    /**
     * @Then the application's kernel should use :expected environment
     */
    public function kernelEnvironmentShouldBe(string $expected): void
    {
        if ($this->kernel->getEnvironment() !== $expected) {
            throw new RuntimeException();
        }
    }
}
