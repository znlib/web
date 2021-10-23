<?php

namespace ZnLib\Web\Symfony4\HttpKernel;

use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\Exception\ControllerDoesNotReturnResponseException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RouteCollection;
use ZnCore\Base\Helpers\InstanceHelper;
use ZnCore\Base\Libs\Container\ContainerAwareTrait;
use ZnCore\Base\Libs\Event\Traits\EventDispatcherTrait;
use ZnLib\Web\Symfony4\HttpKernel\ControllerResolver;
use ZnLib\Web\Symfony4\MicroApp\Enums\ControllerEventEnum;
use ZnLib\Web\Symfony4\MicroApp\Events\ControllerEvent;
use ZnLib\Web\Symfony4\MicroApp\Interfaces\ControllerLayoutInterface;

class HttpFramework implements HttpKernel\HttpKernelInterface
{

    use ContainerAwareTrait;
    use EventDispatcherTrait;

    private $layout;
    private $errorController;
    private $requestStack;
    private $matcher;
    private $resolver;
    private $argumentResolver;
    private $layoutParams = [];

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

    public function getErrorController()
    {
        return $this->errorController ?? null;
    }

    public function setErrorController($errorController): void
    {
        $this->errorController = $errorController;
    }

    /**
     * HttpFramework constructor.
     * @param RouteCollection $routes
     * @param ContainerInterface $container
     * @param HttpFoundation\RequestStack $requestStack
     * @param Routing\Matcher\UrlMatcher $matcher
     * @param \ZnLib\Web\Symfony4\HttpKernel\ControllerResolver $resolver
     * @param HttpKernel\Controller\ArgumentResolver $argumentResolver
     * @todo конф контейнера и использование интерфейсов
     */
    public function __construct(
        RouteCollection $routes,
        ContainerInterface $container,
        HttpFoundation\RequestStack $requestStack,
        Routing\Matcher\UrlMatcher $matcher,
        ControllerResolver $resolver,
        HttpKernel\Controller\ArgumentResolver $argumentResolver
    )
    {
        $this->setContainer($container);

        $this->resolver = $resolver;
        $this->argumentResolver = $argumentResolver;
        $this->requestStack = $requestStack;
        $this->matcher = $matcher;
        $this->dispatcher = $this->getEventDispatcher();

        /*$context = $container->get(Routing\RequestContext::class);
        $matcher = $container->get(Routing\Matcher\UrlMatcher::class);
        $requestStack = $container->get(HttpFoundation\RequestStack::class);
        $controllerResolver = $container->get(HttpKernel\Controller\ControllerResolver::class);
        $argumentResolver = $container->get(HttpKernel\Controller\ArgumentResolver::class);*/

        /*$context = new Routing\RequestContext();
        $matcher = new Routing\Matcher\UrlMatcher($routes, $context);
        $requestStack = new HttpFoundation\RequestStack();
        $controllerResolver = new HttpKernel\Controller\ControllerResolver();
        $argumentResolver = new HttpKernel\Controller\ArgumentResolver();*/

        /*$dispatcher = $container->get(EventDispatcher::class);
        $dispatcher->addSubscriber(new HttpKernel\EventListener\RouterListener($matcher, $requestStack));
        $dispatcher->addSubscriber(new HttpKernel\EventListener\ResponseListener('UTF-8'));
        parent::__construct($dispatcher, $controllerResolver, $requestStack, $argumentResolver);*/
    }

    public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
    {
        // @var ControllerResolver $controllerResolver */
//        $controllerResolver = $this->getContainer()->get(ControllerResolver::class);

        //$this->argumentResolver = $this->container->get(HttpKernel\Controller\ArgumentResolver::class);

        try {
            $this->prepareRequest($request);
            $controller = $this->resolver->getController($request);
            return $this->callAction($request, $controller);
        } catch (\Throwable $e) {
//            dd($this->getErrorController());
            $controllerInstance = $this->getContainer()->get($this->getErrorController());
            $controller = [$controllerInstance, 'handleError'];
//            dd($e);
//            $response = $this->errorHandler($request, $e);
            return $this->callAction($request, $controller, [$request, $e]);
        }
    }

    private function callAction(Request $request, callable $controller, array $arguments = null): Response {
        if($arguments === null) {
            $arguments = $this->argumentResolver->getArguments($request, $controller);
        }
        list($controllerInstance, $actionName) = $controller;
        $this->prepareController($controllerInstance, $actionName, $request);
        $response = $controller(...$arguments);
        return $response;
    }

    private function prepareRequest(Request $request): void {
//        $matcher = $this->getContainer()->get(Routing\Matcher\UrlMatcher::class);
        $uri = rtrim($request->getPathInfo(), '/');
        $attributes = $this->matcher->match($uri);
        if (is_array($attributes['_controller'])) {
            list($controllerClass, $actionName) = $attributes['_controller'];
        } else {
            $controllerClass = $attributes['_controller'];
            $actionName = $attributes['_action'];
        }
        $request->attributes->set('_controller', $controllerClass);
        $request->attributes->set('_action', $actionName);
    }

    private function prepareController(object $controllerInstance, string $actionName, Request $request) {
        if (isset($this->layout) && $controllerInstance instanceof ControllerLayoutInterface) {
            $controllerInstance->setLayout($this->layout);
            $controllerInstance->setLayoutParams($this->getLayoutParams());
        }
        $controllerEvent = new ControllerEvent($controllerInstance, $actionName, $request);
        $this->getEventDispatcher()->dispatch($controllerEvent, ControllerEventEnum::BEFORE_ACTION);
    }

    private function handleRaw(Request $request, int $type = self::MAIN_REQUEST): Response
    {
        $this->requestStack->push($request);

        // request
        $event = new RequestEvent($this, $request, $type);
        $this->dispatcher->dispatch($event, KernelEvents::REQUEST);

        if ($event->hasResponse()) {
            return $this->filterResponse($event->getResponse(), $request, $type);
        }

        // load controller
        if (false === $controller = $this->resolver->getController($request)) {
            throw new NotFoundHttpException(sprintf('Unable to find the controller for path "%s". The route is wrongly configured.', $request->getPathInfo()));
        }

        $event = new \Symfony\Component\HttpKernel\Event\ControllerEvent($this, $controller, $request, $type);
        $this->dispatcher->dispatch($event, KernelEvents::CONTROLLER);
        $controller = $event->getController();

        // controller arguments
        $arguments = $this->argumentResolver->getArguments($request, $controller);

        $event = new ControllerArgumentsEvent($this, $controller, $arguments, $request, $type);
        $this->dispatcher->dispatch($event, KernelEvents::CONTROLLER_ARGUMENTS);
        $controller = $event->getController();
        $arguments = $event->getArguments();

        // call controller
        $response = $controller(...$arguments);

        // view
        if (!$response instanceof Response) {
            $event = new ViewEvent($this, $request, $type, $response);
            $this->dispatcher->dispatch($event, KernelEvents::VIEW);

            if ($event->hasResponse()) {
                $response = $event->getResponse();
            } else {
                $msg = sprintf('The controller must return a "Symfony\Component\HttpFoundation\Response" object but it returned %s.', $this->varToString($response));

                // the user may have forgotten to return something
                if (null === $response) {
                    $msg .= ' Did you forget to add a return statement somewhere in your controller?';
                }

                throw new ControllerDoesNotReturnResponseException($msg, $controller, __FILE__, __LINE__ - 17);
            }
        }

        return $this->filterResponse($response, $request, $type);
    }
}
