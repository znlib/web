<?php

namespace ZnLib\Web\Symfony4\MicroApp;

use Illuminate\Container\EntryNotFoundException;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\NoConfigurationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use ZnCore\Base\Exceptions\DeprecatedException;
use ZnCore\Base\Helpers\InstanceHelper;
use ZnCore\Base\Libs\Event\Traits\EventDispatcherTrait;
use ZnLib\Web\Symfony4\MicroApp\Enums\ControllerEventEnum;
use ZnLib\Web\Symfony4\MicroApp\Events\ControllerEvent;
use ZnLib\Web\Symfony4\MicroApp\Interfaces\ControllerLayoutInterface;

class MicroApp
{

    use EventDispatcherTrait;

    /** @var RouteCollection */
    private $routes;
    private $routingConfigurator;
    private $layout;
    private $layoutParams = [];

    private $errorController;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container = null, RouteCollection $routes = null)
    {
        if ($container) {
            $this->container = $container;
        }
        $this->routes = $routes ?: $this->container->get(RouteCollection::class);
        $this->routingConfigurator = $this->createRoutingConfigurator($this->routes);
    }

    public function getRoutes(): RouteCollection
    {
        return $this->routes;
    }

    public function setRoutes(RouteCollection $routes): void
    {
        $this->routes = $routes;
    }

    public function getLayout(): ?string
    {
        return $this->layout;
    }

    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    public function getLayoutParams(): array
    {
        return $this->layoutParams;
    }

    public function setLayoutParams(array $layoutParams): void
    {
        $this->layoutParams = $layoutParams;
    }

    public function addLayoutParam(string $name, $value): void
    {
        $this->layoutParams[$name] = $value;
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function addModules(array $modulesConfig)
    {
        foreach ($modulesConfig as $moduleClass) {
            if (is_object($moduleClass)) {
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
//        $request = $request ?: $this->container->get(Request::class);
        $request = $request ?: Request::createFromGlobals();
        $matcher = $this->createMatcher($this->routes, $request);
        try {
            $response = $this->runAction($matcher, $request);
        } catch (\Exception $e) {
            $response = $this->errorHandler($request, $e);
        }
        return $response;
    }

    public function getErrorController()
    {
        return $this->errorController ?? null;
    }

    public function setErrorController($errorController): void
    {
        $this->errorController = $errorController;
    }

    private function createRoutingConfigurator(RouteCollection $routes): RoutingConfigurator
    {
        $fileLocator = new FileLocator();
        $fileLoader = new PhpFileLoader($fileLocator);
        return new RoutingConfigurator($routes, $fileLoader, __FILE__, __FILE__);
    }

    private function errorHandler(Request $request, \Throwable $e): Response
    {
        $errorController = $this->getErrorController();
        if ($errorController) {
            if (!is_object($errorController)) {
                $errorController = $this->container->get($errorController);
            }
            $attributes = [
                \Exception::class => $e,
            ];
            return $this->callControllerAction($errorController, 'handleError', $request, $attributes);
//            return $errorController->handleError($request, $e);
        }
        if ($e instanceof NoConfigurationException) {
            $response = new Response('Not config', 500);
        } elseif ($e instanceof EntryNotFoundException) {
            $response = new Response('Not found class in container for DI in ' . $e->getMessage(), 500);
        } elseif ($e instanceof ResourceNotFoundException) {
            $response = new Response('Not found route', 404);
        } elseif ($e instanceof \Throwable) {
            $response = new Response(get_class($e) . ' ' . $e->getMessage());
        }
        return $response;
    }

    private function runAction(UrlMatcherInterface $matcher, Request $request): Response
    {
        $uri = rtrim($request->getPathInfo(), '/');
        $attributes = $matcher->match($uri);
        if (is_array($attributes['_controller'])) {
            list($controllerClass, $actionName) = $attributes['_controller'];
        } else {
            $controllerClass = $attributes['_controller'];
            $actionName = $attributes['_action'];
        }
//        dd($attributes);
        $controllerInstance = $this->container->get($controllerClass);
        return $this->callControllerAction($controllerInstance, $actionName, $request);
    }

    private function callControllerAction(object $controllerInstance, string $actionName, Request $request, array $attributes = []): Response
    {
        if (isset($this->layout) && $controllerInstance instanceof ControllerLayoutInterface) {
            $controllerInstance->setLayout($this->layout);
            $controllerInstance->setLayoutParams($this->getLayoutParams());
        }

        $attributes[Request::class] = $request;
//        $this->container->bind(Request::class, function() use ($request) {return $request;});
        //$response = call_user_func_array([$controllerInstance, $actionName], [$request]);
        /*$params = $request->query->all();
        $params[] = $request;*/

        $controllerEvent = new ControllerEvent($controllerInstance, $actionName, $request);
        $this->getEventDispatcher()->dispatch($controllerEvent, ControllerEventEnum::BEFORE_ACTION);

//        $response = $this->container->call([$controllerInstance, $actionName], $attributes);
        $response = InstanceHelper::callMethod($controllerInstance, $actionName, $attributes);

        return $response;
    }

    private function createMatcher(RouteCollection $routes, Request $request): UrlMatcherInterface
    {
        /** @var RequestContext $context */
        $context = $this->container->get(RequestContext::class); //new RequestContext;
        $context->fromRequest($request);
//        dd($routes);
        //dd($this->container->get(RouteCollection::class));
//        $matcher = $this->container->get(UrlMatcher::class);
        $matcher = new UrlMatcher($routes, $context);
        return $matcher;
    }
}
