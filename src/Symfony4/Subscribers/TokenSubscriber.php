<?php

namespace ZnLib\Web\Symfony4\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use ZnCore\Base\Exceptions\InvalidConfigException;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnLib\Web\Symfony4\MicroApp\Enums\ControllerEventEnum;
use ZnLib\Web\Symfony4\MicroApp\Events\ControllerEvent;
use ZnLib\Web\Symfony4\MicroApp\Interfaces\ControllerAccessInterface;
use ZnUser\Rbac\Domain\Interfaces\Services\ManagerServiceInterface;

class TokenSubscriber implements EventSubscriberInterface
{

    private $managerService;
//    private $authService;

    public function __construct(
        ManagerServiceInterface $managerService
//        AuthServiceInterface $authService
    )
    {
        $this->managerService = $managerService;
//        $this->authService = $authService;
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEventEnum::BEFORE_ACTION => 'onBeforeRunAction',
        ];
    }

    public function onBeforeRunAction(ControllerEvent $event)
    {
        $controller = $event->getController();
        if (!$controller instanceof ControllerAccessInterface) {
            //throw new InvalidConfigException('Controller not instance of "ControllerAccessInterface".');
        }
        if ($controller instanceof ControllerAccessInterface) {
            $access = $controller->access();
            $actionPermissions = ArrayHelper::getValue($access, $event->getAction());
            if (empty($actionPermissions)) {
                throw new InvalidConfigException('Empty permissions.');
            }
            if ($actionPermissions) {
                $this->managerService->checkMyAccess($actionPermissions);
            }
        }
    }
}
