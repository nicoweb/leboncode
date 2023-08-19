<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\Symfony\Symfony42\Rector\MethodCall\ContainerGetToConstructorInjectionRector;
use Rector\Symfony\Symfony62\Rector\Class_\MessageHandlerInterfaceToAttributeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__.'/public',
        __DIR__.'/app/Core/src',
        __DIR__.'/app/Core/Features/Context',
        __DIR__.'/app/Module/User/src',
        __DIR__.'/app/Module/User/Features/Context',
    ]);

    $rectorConfig->symfonyContainerXml(
        __DIR__.'/var/cache/dev/NicolasLefevre_LeBonCode_Core_Infrastructure_Framework_KernelDevDebugContainer.xml',
    );

    $rectorConfig->sets([
        SymfonySetList::SYMFONY_63,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
        LevelSetList::UP_TO_PHP_82,
    ]);

    $rectorConfig->skip([
        MessageHandlerInterfaceToAttributeRector::class,
        ContainerGetToConstructorInjectionRector::class,
    ]);
};
