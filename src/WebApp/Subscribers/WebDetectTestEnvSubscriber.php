<?php

namespace ZnLib\Web\WebApp\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\Event;
use ZnCore\App\Enums\AppEventEnum;
use ZnLib\Web\WebApp\Libs\EnvDetector\WebEnvDetector;

class WebDetectTestEnvSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            AppEventEnum::BEFORE_INIT_ENV => 'onBeforeInitEnv',
        ];
    }

    public function onBeforeInitEnv(Event $event)
    {
        $envDetector = new WebEnvDetector();
        $isTest = $envDetector->isTest();
        if ($isTest) {
            $_ENV['APP_ENV'] = 'test';
        }
        $_ENV['APP_MODE'] = $isTest ? 'test' : 'main';
    }
}
