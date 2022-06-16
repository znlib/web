<?php

namespace ZnLib\Web\Symfony4\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use ZnCore\Base\Exceptions\InvalidConfigException;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnLib\Web\Symfony4\MicroApp\Enums\ControllerEventEnum;
use ZnLib\Web\Symfony4\MicroApp\Interfaces\ControllerAccessInterface;
use ZnUser\Rbac\Domain\Interfaces\Services\ManagerServiceInterface;

class TokenSubscriber implements EventSubscriberInterface
{

    private $managerService;

    public function __construct(
        ManagerServiceInterface $managerService
    )
    {
        $this->managerService = $managerService;
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEventEnum::BEFORE_ACTION => 'onBeforeRunAction',
        ];
    }

    public function onBeforeRunAction(ControllerEvent $event)
    {
        $callable = $event->getController();
        if (is_array($callable)) {
            $controller = $callable[0];
            $action = $callable[1];
        }

        if (!$controller instanceof ControllerAccessInterface) {
            //throw new InvalidConfigException('Controller not instance of "ControllerAccessInterface".');
        }
        if ($controller instanceof ControllerAccessInterface) {
            $access = $controller->access();
            $actionPermissions = ArrayHelper::getValue($access, $action);
            if (empty($actionPermissions)) {
                throw new InvalidConfigException('Empty permissions.');
            }
            if ($actionPermissions) {
                $this->managerService->checkMyAccess($actionPermissions);
            }
        }
    }
}
