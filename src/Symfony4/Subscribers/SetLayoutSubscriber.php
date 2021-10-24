<?php

namespace ZnLib\Web\Symfony4\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use ZnLib\Web\Symfony4\MicroApp\Interfaces\ControllerLayoutInterface;

class SetLayoutSubscriber implements EventSubscriberInterface
{

    private $layout;
    private $layoutParams = [];

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    public function onKernelController(\Symfony\Component\HttpKernel\Event\ControllerEvent $event)
    {
        $controller = $event->getController();
        list($controllerInstance, $actionName) = $controller;
        if (isset($this->layout) && $controllerInstance instanceof ControllerLayoutInterface) {
            $controllerInstance->setLayout($this->layout);
            $controllerInstance->setLayoutParams($this->getLayoutParams());
        }
//        $controllerEvent = new ControllerEvent($controllerInstance, $actionName, $request);
//        $this->getEventDispatcher()->dispatch($controllerEvent, ControllerEventEnum::BEFORE_ACTION);
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
}
