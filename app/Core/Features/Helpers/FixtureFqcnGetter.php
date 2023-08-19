<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Features\Helpers;

use LogicException;
use Symfony\Component\Finder\Finder;

final class FixtureFqcnGetter
{
    /**
     * Gets a fixture FQCN from the fixture directory or subdirectory.
     */
    public static function getFixture(string $scenarioTitle): string
    {
        $scenarioTitle = str_replace('\'', '', $scenarioTitle);
        $scenarioTitleNormalized = self::removeContentBetweenParenthesis($scenarioTitle);
        $fixtureWords = array_map('ucfirst', explode(' ', $scenarioTitleNormalized));
        $fixtureClassName = implode('', $fixtureWords).'Fixture';

        $finder = new Finder();
        $finder->files()->in(__DIR__.'/../Fixture')->name($fixtureClassName.'.php');

        if (!$finder->hasResults()) {
            throw new LogicException(sprintf('The fixture "%s" is not defined.', $fixtureClassName));
        }

        if ($finder->count() > 1) {
            throw new LogicException(sprintf('The fixture "%s" is defined more than once.', $fixtureClassName));
        }

        $iterator = $finder->getIterator();
        $iterator->rewind();

        return $iterator->current()->getPathname();
    }

    private static function removeContentBetweenParenthesis(string $scenarioTitle): string
    {
        return preg_replace('/\([^)]+\)/', '', $scenarioTitle);
    }
}
