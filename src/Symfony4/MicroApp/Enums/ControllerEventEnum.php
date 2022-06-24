<?php

namespace ZnLib\Web\Symfony4\MicroApp\Enums;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;
use ZnCore\Base\Develop\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

class ControllerEventEnum
{

    const BEFORE_ACTION = 'before_action';

}
