<?php

namespace ZnLib\Web\Symfony4\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use ZnBundle\User\Domain\Interfaces\Services\IdentityServiceInterface;
use ZnCore\Base\Exceptions\InvalidConfigException;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Libs\DotEnv\DotEnv;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnLib\Web\Symfony4\MicroApp\Enums\ControllerEventEnum;
use ZnLib\Web\Symfony4\MicroApp\Events\ControllerEvent;
use ZnLib\Web\Symfony4\MicroApp\Interfaces\ControllerAccessInterface;
use ZnLib\Web\Symfony4\MicroApp\Libs\CookieValue;
use ZnSandbox\Sandbox\Casbin\Domain\Interfaces\Services\ManagerServiceInterface;

class WebAuthSubscriber implements EventSubscriberInterface
{

    private $authService;
    private $identityService;
    private $session;

    public function __construct(
        AuthServiceInterface $authService,
        IdentityServiceInterface $identityService,
        SessionInterface $session
    )
    {
        $this->authService = $authService;
        $this->identityService = $identityService;
        $this->session = $session;
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEventEnum::BEFORE_ACTION => 'onBeforeRunAction',
        ];
    }

    public function onBeforeRunAction(ControllerEvent $event)
    {
        $identityArray = $this->session->get('user.identity');
        if(!$identityArray) {
            $identityIdCookie = $event->getRequest()->cookies->get('identityId');
            if($identityIdCookie) {
                $cookieValue = new CookieValue(DotEnv::get('CSRF_TOKEN_ID'));
                $identityId = $cookieValue->decode($identityIdCookie);
                $identity = $this->identityService->oneById($identityId);
                $this->authService->setIdentity($identity);
                $this->session->set('user.identity', EntityHelper::toArray($identity));
            }
        }
    }
}
