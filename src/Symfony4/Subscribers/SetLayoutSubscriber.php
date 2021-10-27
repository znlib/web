<?php

namespace ZnLib\Web\Symfony4\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use ZnCore\Base\Helpers\LoadHelper;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Libs\App\Helpers\ContainerHelper;
use ZnLib\Web\Symfony4\MicroApp\Interfaces\ControllerLayoutInterface;
use ZnLib\Web\View\View;

class SetLayoutSubscriber implements EventSubscriberInterface
{

    private $layout;
    private $layoutParams = [];
    private $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    public function onKernelController(\Symfony\Component\HttpKernel\Event\ControllerEvent $event)
    {
        $controller = $event->getController();
        list($controllerInstance, $actionName) = $controller;
        if (/*isset($this->layout) &&*/ $controllerInstance instanceof ControllerLayoutInterface) {
            $controllerInstance->setLayout(null/*$this->layout*/);
//            $controllerInstance->setLayoutParams($this->getLayoutParams());
        }
//        $controllerEvent = new ControllerEvent($controllerInstance, $actionName, $request);
//        $this->getEventDispatcher()->dispatch($controllerEvent, ControllerEventEnum::BEFORE_ACTION);
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $params = $this->getLayoutParams();
        $params['content'] = $response->getContent();
//        $view = ContainerHelper::getContainer()->get(View::class);
        $content = $this->view->renderFile($this->layout, $params);
        $response->setContent($content);
    }

    public function getLayout(): ?string
    {
        return $this->layout;
    }

    public function setLayout(?string $layout): void
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
