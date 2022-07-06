<?php

namespace ZnLib\Web\WebApp\Libs;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use ZnCore\Container\Traits\ContainerAwareTrait;

class ControllerResolver implements ControllerResolverInterface
{

    use ContainerAwareTrait;

    public function __construct(ContainerInterface $container, UrlMatcher $matcher)
    {
        $this->matcher = $matcher;
        $this->setContainer($container);
    }

    public function getController(Request $request)
    {
        $controllerClass = $request->attributes->get('_controller');
        $actionName = $request->attributes->get('_action');
        $controllerInstance = $this->getContainer()->get($controllerClass);
        return [$controllerInstance, $actionName];
    }
}
