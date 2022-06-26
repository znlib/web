<?php

namespace ZnLib\Web\WebApp\Libs\BundleLoaders;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\RouteCollection;
use ZnCore\Base\Bundle\Base\BaseLoader;

class SymfonyRoutesLoader extends BaseLoader
{

    private function createRoutingConfigurator($routes): RoutingConfigurator
    {
        $fileLocator = new FileLocator();
        $fileLoader = new PhpFileLoader($fileLocator);
        $routingConfigurator = new RoutingConfigurator($routes, $fileLoader, __FILE__, __FILE__);
        return $routingConfigurator;
    }

    public function loadAll(array $bundles): void
    {
        $routes = $this->container->get(RouteCollection::class); //new RouteCollection();
        $routingConfigurator = $this->createRoutingConfigurator($routes);
        foreach ($bundles as $bundle) {
            $loadedConfig = $this->load($bundle);
            if ($loadedConfig) {
                foreach ($loadedConfig as $configFile) {
                    $closure = include $configFile;
                    call_user_func($closure, $routingConfigurator);
//                    $closure($routingConfigurator);
                }
            }
        }
    }
}
