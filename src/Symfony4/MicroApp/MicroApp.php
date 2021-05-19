<?php

namespace ZnLib\Web\Symfony4\MicroApp;

use Illuminate\Container\EntryNotFoundException;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\NoConfigurationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use ZnCore\Base\Exceptions\DeprecatedException;
use ZnCore\Base\Libs\Event\Traits\EventDispatcherTrait;
use ZnCore\Base\Libs\Scenario\Interfaces\RunInterface;
use ZnLib\Web\Symfony4\MicroApp\Enums\ControllerEventEnum;
use ZnLib\Web\Symfony4\MicroApp\Events\ControllerEvent;

class MicroApp
{

    use EventDispatcherTrait;

    /** @var RouteCollection */
    private $routes;
    private $routingConfigurator;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container = null, RouteCollection $routes = null)
    {
        if($container) {
            $this->container = $container;
        }
        $this->routes = $routes ?: $this->container->get(RouteCollection::class);
        $fileLocator = new FileLocator();
        $fileLoader = new PhpFileLoader($fileLocator);
        $this->routingConfigurator = new RoutingConfigurator($this->routes, $fileLoader, __FILE__, __FILE__);
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param int|null $level
     * @deprecated
     * @see EnvHelper::setErrorVisible()
     */
    public function setErrorLevel(int $level = null)
    {
        throw new DeprecatedException();
        if ($level === null) {
            error_reporting(0);
            ini_set('display_errors', '0');
        } else {
            error_reporting($level);
            ini_set('display_errors', '1');
        }
    }

    public function addModules(array $modulesConfig)
    {
        foreach ($modulesConfig as $moduleClass) {
            if(is_object($moduleClass)) {
                $moduleInstance = $moduleClass;
            } else {
                $moduleInstance = $this->container->get($moduleClass);
            }
            $this->addModule($moduleInstance);
        }
    }

    public function addModule(BaseModule $module)
    {
        $module->configContainer($this->container);
        $module->configRoutes($this->routes);
        $module->configRouting($this->routingConfigurator);
    }

    public function run(Request $request = null): Response
    {
        $request = $request ?: Request::createFromGlobals();
        $matcher = $this->createMatcher($this->routes, $request);
        try {
            $response = $this->runAction($matcher, $request);
        } catch (\Exception $e) {
            $response = $this->errorHandler($request, $e);
        }
        return $response;
    }

    private $errorController;

    public function getErrorController()
    {
        return $this->errorHandler ?? null;
    }

    public function setErrorController($errorController): void
    {
        $this->errorHandler = $errorController;
    }
    
    private function errorHandler(Request $request, \Throwable $e): Response {
        $errorController = $this->getErrorController();
        if($errorController) {
            if(!is_object($errorController)) {
                $errorController = $this->container->get($errorController);
            }
            return $errorController->handleError($request, $e);
        }
        if($e instanceof NoConfigurationException) {
            $response = new Response('Not config', 500);
        } elseif($e instanceof EntryNotFoundException) {
            $response = new Response('Not found class in container for DI in ' . $e->getMessage(), 500);
        } elseif($e instanceof ResourceNotFoundException) {
            $response = new Response('Not found route', 404);
        } elseif($e instanceof \Throwable) {
            $response = new Response(get_class($e) . '  ' . $e->getMessage());
        }
        return $response;
    }

    private function runAction(UrlMatcherInterface $matcher, Request $request): Response
    {
        $attributes = $matcher->match($request->getPathInfo());
        if(is_array($attributes['_controller'])) {
            list($controller, $actionName) = $attributes['_controller'];
        } else {
            $controller = $attributes['_controller'];
            $actionName = $attributes['_action'];
        }
        $controllerInstance = $this->container->get($controller);
        $attributes[Request::class] = $request;
//        $this->container->bind(Request::class, function() use ($request) {return $request;});
        //$response = call_user_func_array([$controllerInstance, $actionName], [$request]);
        /*$params = $request->query->all();
        $params[] = $request;*/

        $controllerEvent = new ControllerEvent($controllerInstance, $actionName, $request);
        $this->getEventDispatcher()->dispatch($controllerEvent, ControllerEventEnum::BEFORE_ACTION);

        $response = $this->container->call([$controllerInstance, $actionName], $attributes);
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
