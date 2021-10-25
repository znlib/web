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

class UnauthorizedSubscriber implements EventSubscriberInterface
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
//        ArgumentResolverInterface $argumentResolver,
        Session $session
    )
    {
        $this->urlGenerator = $urlGenerator;
        $this->controllerResolver = $controllerResolver;
//        $this->argumentResolver = $argumentResolver;
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

        if ($event->getThrowable() instanceof \ZnBundle\User\Domain\Exceptions\UnauthorizedException) {
            $currentUrl = $event->getRequest()->getRequestUri();
            $this->session->set(WebUserEnum::UNAUTHORIZED_URL_SESSION_KEY, $currentUrl);
            $authUrl = $this->urlGenerator->generate($this->authUrl);
            $response = new RedirectResponse($authUrl);
            $event->setResponse($response);
        } else {

            //dd($event->getRequest()->attributes->get('_controller'));

            $request = $event->getRequest()->duplicate();

            $request->attributes->set('_controller', \ZnSandbox\Sandbox\Error\Symfony4\Web\Controllers\ErrorController::class);
            $request->attributes->set('_action', 'handleError');

//            dd($request);

            $controller = $this->controllerResolver->getController($request);
            $arguments = [
                $request,
                $event->getThrowable()
            ];
            $response = $controller(...$arguments);
            $event->setResponse($response);

            //dd($response);

            //$args = $this->argumentResolver->getArguments($request, $controller);

            //dd($args);

//            $response = $event->getKernel()->handle($request, KernelInterface::SUB_REQUEST, false);
//            $event->setResponse($response);
//            dd($response);

//            dd($controller);


            /*$errorController = new ErrorController($event->getKernel(), $controller, new HtmlErrorRenderer());
            $response = $errorController->preview(new Request(), 500);
            $event->setResponse($response);*/
        }
    }
}
