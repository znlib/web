<?php

namespace ZnLib\Web\Symfony4\HttpKernel;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\Exception\ControllerDoesNotReturnResponseException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing;
use Symfony\Component\Routing\RouteCollection;
use ZnCore\Base\Libs\Container\ContainerAwareTrait;
use ZnCore\Base\Libs\Event\Traits\EventDispatcherTrait;
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
    }

    public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
    {
        try {
            $this->prepareRequest($request);
            $controller = $this->resolver->getController($request);
            $arguments = $this->argumentResolver->getArguments($request, $controller);
            return $this->callAction($request, $controller, $arguments);
        } catch (\Throwable $e) {
            $controllerInstance = $this->getContainer()->get($this->getErrorController());
            $controller = [$controllerInstance, 'handleError'];
            return $this->callAction($request, $controller, [$request, $e]);
        }
    }

    private function callAction(Request $request, callable $controller, array $arguments = null): Response
    {
        /*if ($arguments === null) {
            $arguments = $this->argumentResolver->getArguments($request, $controller);
        }*/
        list($controllerInstance, $actionName) = $controller;
        $this->prepareController($controllerInstance, $actionName, $request);
        $response = $controller(...$arguments);
        return $response;
    }

    private function prepareRequest(Request $request): void
    {
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

    private function prepareController(object $controllerInstance, string $actionName, Request $request)
    {
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
