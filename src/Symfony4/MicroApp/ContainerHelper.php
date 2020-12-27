<?php

namespace ZnLib\Web\Symfony4\MicroApp;

use Illuminate\Container\Container;
use Psr\Container\ContainerInterface;

class ContainerHelper
{

    private static $container;

    public static function setContainer(object $container)
    {
        self::$container = $container;
    }

    /**
     * @return ContainerInterface|null
     */
    public static function getContainer(): ?object
    {
        if(isset(self::$container)) {
            return self::$container;
        }
        if(class_exists(Container::class)) {
            return Container::getInstance();
        }
        return null;
    }

    public static function configureContainer(ContainerInterface $container, array $containerConfig)
    {
        $container->singleton(ContainerInterface::class, Container::class);
        $container->singleton(Container::class, function () use ($container) {
            return $container;
        });
        foreach ($containerConfig['definitions'] as $abstract => $concrete) {
            $container->bind($abstract, $concrete, true);
        }
        foreach ($containerConfig['singletons'] as $abstract => $concrete) {
            $container->singleton($abstract, $concrete);
        }
    }
}
