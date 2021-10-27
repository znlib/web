<?php

namespace ZnLib\Web\Symfony4\Subscribers;

use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Controller\ErrorController;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use ZnBundle\User\Symfony4\Web\Enums\WebUserEnum;
use ZnLib\Web\Symfony4\MicroApp\Interfaces\ControllerLayoutInterface;

class ErrorHandleSubscriber implements EventSubscriberInterface
{

    private $authUrl = 'user/auth';
    private $urlGenerator;
    private $session;
    private $errorController;
    private $controllerResolver;
    private $argumentResolver;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        ControllerResolverInterface $controllerResolver,
        Session $session
    )
    {
        $this->urlGenerator = $urlGenerator;
        $this->controllerResolver = $controllerResolver;
        $this->session = $session;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $request = $event->getRequest()->duplicate();
        $request->attributes->set('_controller', \ZnSandbox\Sandbox\Error\Symfony4\Web\Controllers\ErrorController::class);
        $request->attributes->set('_action', 'handleError');
        $controller = $this->controllerResolver->getController($request);
        list($controllerInstance, $actionName) = $controller;
        if($controllerInstance instanceof ControllerLayoutInterface) {
            $controllerInstance->setLayout(null);
        }
        $arguments = [
            $request,
            $event->getThrowable()
        ];
        $response = $controller(...$arguments);
        $event->setResponse($response);
    }
}
