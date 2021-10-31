<?php

namespace ZnLib\Web\Symfony4\Enums;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppEventEnum
{

    public const BEFORE_INIT_ENV = 'app.beforeInitEnv';
    public const AFTER_INIT_ENV = 'app.afterInitEnv';
}
