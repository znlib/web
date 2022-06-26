<?php

namespace ZnLib\Web\Symfony4\MicroApp;

use Illuminate\Container\Container;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\RouteCollection;
use ZnCore\Base\Develop\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

abstract class BaseModule
{

    public function configContainer(Container $container)
    {

    }

    /**
     * @param RouteCollection $routes
     * @deprecated
     * @see configRouting
     */
    public function configRoutes(RouteCollection $routes)
    {

    }

    public function configRouting(RoutingConfigurator $routes)
    {

    }
}
