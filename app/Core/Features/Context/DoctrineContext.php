<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use NicolasLefevre\LeBonCode\Core\Features\Helpers\FixtureFqcnGetter;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Component\HttpKernel\KernelInterface;

final readonly class DoctrineContext implements Context
{
    public function __construct(
        private KernelInterface $kernel,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @BeforeScenario
     */
    public function clearDatabase(): void
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $purger->purge();
    }

    /**
     * @BeforeScenario @fixtures
     */
    public function loadFixtures(BeforeScenarioScope $scenarioScope): void
    {
        $scenarioTitle = $scenarioScope->getScenario()->getTitle();
        if (!$scenarioTitle) {
            throw new LogicException('Scenario title is null.');
        }
        $loader = new ContainerAwareLoader($this->kernel->getContainer());
        $loader->loadFromFile(FixtureFqcnGetter::getFixture($scenarioTitle));

        $executor = new ORMExecutor($this->entityManager);
        $executor->execute($loader->getFixtures(), true);
    }
}
