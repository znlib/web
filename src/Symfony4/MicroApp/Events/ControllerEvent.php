<?php

namespace ZnLib\Web\Symfony4\MicroApp\Events;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

final class ControllerEvent extends Event
{

    private $controller;
    private $action;
    private $request;

    public function __construct(object $controller, string $action, Request $request)
    {
        $this->setController($controller);
        $this->setAction($action);
        $this->setRequest($request);
    }

    public function getController(): object
    {
        return $this->controller;
    }

    public function setController(object $controller): void
    {
        $this->controller = $controller;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

}
