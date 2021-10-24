<?php

namespace ZnLib\Web\Symfony4\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use ZnBundle\User\Symfony4\Web\Enums\WebUserEnum;

class UnauthorizedSubscriber implements EventSubscriberInterface
{

    private $authUrl = 'user/auth';
    private $urlGenerator;
    private $session;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        Session $session
    )
    {
        $this->urlGenerator = $urlGenerator;
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
        }
    }
}
