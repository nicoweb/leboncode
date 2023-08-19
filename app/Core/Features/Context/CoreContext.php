<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Features\Context;

use Behat\Behat\Context\Context;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class CoreContext implements Context
{
    public function __construct(
        protected KernelBrowser $browser,
    ) {
    }
}
