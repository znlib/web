<?php

namespace ZnLib\Web\Symfony4\MicroApp;

use Illuminate\Container\Container;
use Symfony\Component\Routing\RouteCollection;

abstract class BaseModule
{

    public function configContainer(Container $container)
    {

    }

    public function configRoutes(RouteCollection $routes)
    {

    }
}
