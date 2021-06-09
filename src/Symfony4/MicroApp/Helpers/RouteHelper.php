<?php

namespace ZnLib\Web\Symfony4\MicroApp\Helpers;

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use ZnCore\Base\Helpers\ClassHelper;

class RouteHelper
{

    public static function generateCrud(RoutingConfigurator $routes, string $controllerClass, string $basePath)
    {
        $controllerClassName = ClassHelper::getClassOfClassName($controllerClass);
        //dd($class);
        $routes
            ->add($controllerClassName . '_index', $basePath)
            ->controller([$controllerClass, 'index'])
            ->methods(['GET', 'POST']);
        $routes
            ->add($controllerClassName . '_view', $basePath . '/view')
            ->controller([$controllerClass, 'view'])
            ->methods(['GET', 'POST']);
        $routes
            ->add($controllerClassName . '_update', $basePath . '/update')
            ->controller([$controllerClass, 'update'])
            ->methods(['GET', 'POST']);
        $routes
            ->add($controllerClassName . '_delete', $basePath . '/delete')
            ->controller([$controllerClass, 'delete'])
            ->methods(['GET', 'POST']);
        $routes
            ->add($controllerClassName . '_create', $basePath . '/create')
            ->controller([$controllerClass, 'create'])
            ->methods(['GET', 'POST']);
    }
}
