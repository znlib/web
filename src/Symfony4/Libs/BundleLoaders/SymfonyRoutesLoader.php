<?php

namespace ZnLib\Web\Symfony4\Libs\BundleLoaders;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\RouteCollection;
use ZnCore\Base\Libs\App\Interfaces\ConfigManagerInterface;
use ZnCore\Base\Libs\App\Loaders\BundleLoaders\BaseLoader;

class SymfonyRoutesLoader extends BaseLoader
{

    /*public function __construct(ConfigManagerInterface $configManager)
    {
        $this->setConfigManager($configManager);
    }*/

    public function loadAll(array $bundles): array
    {
        /** @todo костыль */
        $this->container->singleton(RouteCollection::class, RouteCollection::class);
        $routes = $this->container->get(RouteCollection::class); //new RouteCollection();
//dd($routes);
        $fileLocator = new FileLocator();
        $fileLoader = new PhpFileLoader($fileLocator);
        $routingConfigurator = new RoutingConfigurator($routes, $fileLoader, __FILE__, __FILE__);

        foreach ($bundles as $bundle) {
            $loadedConfig = $this->load($bundle);
            if ($loadedConfig) {
                foreach ($loadedConfig as $configFile) {
                    $closure = include $configFile;
                    $closure($routingConfigurator);
                }
            }
        }
        $config['routeCollection'] = $routes;
        //if($this->hasConfigManager()) {
            $this->getConfigManager()->set('routeCollection', $routes);
        //}
        return $config;
    }
}
