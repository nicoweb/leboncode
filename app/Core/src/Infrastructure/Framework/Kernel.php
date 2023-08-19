<?php

namespace NicolasLefevre\LeBonCode\Core\Infrastructure\Framework;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private function getConfigDir(): string
    {
        return $this->getAppDir().'/Core/config';
    }

    public function getAppDir(): string
    {
        return $this->getProjectDir().'/app';
    }

    public function getCoreDir(): string
    {
        return $this->getAppDir().'/Core/src';
    }

    /**
     * @phpstan-ignore-next-line
     */
    private function configureContainer(
        ContainerConfigurator $container,
        LoaderInterface $loader,
        ContainerBuilder $builder
    ): void {
        $configDir = $this->getConfigDir();

        $container->import($configDir.'/{packages}/*.yaml');
        $container->import($configDir.'/{packages}/'.$this->environment.'/*.yaml');

        $appDir = $this->getAppDir();

        $container->import($configDir.'/services.yaml');
        $container->import($configDir.'/{services}_'.$this->environment.'.yaml');
        $container->import($appDir.'/{Module}/**/config/services.yaml');
        $container->import($appDir.'/{Module}/**/config/{services}_'.$this->environment.'.yaml');
    }
}
