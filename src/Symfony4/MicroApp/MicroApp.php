<?php

namespace ZnLib\Web\Symfony4\MicroApp;

use Illuminate\Container\EntryNotFoundException;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\NoConfigurationException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class MicroApp
{

    private $routes;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container, RouteCollection $routes = null)
    {
        $this->container = $container;
        $this->routes = $this->routes ?: $this->container->get(RouteCollection::class);
    }

    public function setErrorLevel(int $level = null)
    {
        if ($level === null) {
            error_reporting(0);
            ini_set('display_errors', '0');
        } else {
            error_reporting($level);
            ini_set('display_errors', '1');
        }
    }

    public function addModule(BaseModule $module)
    {
        $module->configContainer($this->container);
        $module->configRoutes($this->routes);
    }

    public function run(Request $request = null): Response
    {
        $request = $request ?: Request::createFromGlobals();
        $matcher = $this->createMatcher($this->routes, $request);
        try {
            $response = $this->runAction($matcher, $request);
        } catch (NoConfigurationException $e) {
            $response = new Response('Not config', 404);
        } catch (EntryNotFoundException $e) {
            $response = new Response('Not Found', 404);
        } catch (\Exception $e) {
            $response = new Response($e->getMessage());
        }
        return $response;
    }

    private function runAction(UrlMatcherInterface $matcher, Request $request): Response
    {
        $attributes = $matcher->match($request->getPathInfo());
        $actionName = $attributes['_action'];
        $controllerInstance = $this->container->get($attributes['_controller']);
        //$response = call_user_func_array([$controllerInstance, $actionName], [$request]);
        $params = $request->query->all();
        $params[] = $request;
        $response = $this->container->call([$controllerInstance, $actionName], $params);
        return $response;
    }

    private function createMatcher(RouteCollection $routes, Request $request): UrlMatcherInterface
    {
        $context = new RequestContext;
        $context->fromRequest($request);
        $matcher = new UrlMatcher($routes, $context);
        return $matcher;
    }
}
