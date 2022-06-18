<?php

namespace ZnLib\Web\Libs\EnvDetector;

use ZnCore\Base\Libs\App\Interfaces\EnvDetectorInterface;

class WebEnvDetector implements EnvDetectorInterface
{

    public function isMatch(): bool
    {
        global $_GET, $_SERVER;
        return isset($_SERVER['REQUEST_URI']);
    }

    public function isTest(): bool
    {
        global $_GET, $_SERVER;
//        $isWebTest = isset($_GET['env']) && $_GET['env'] == 'test';
        $isWebTest = (isset($_SERVER['HTTP_ENV_NAME']) && $_SERVER['HTTP_ENV_NAME'] == 'test') || (isset($_GET['env']) && $_GET['env'] == 'test');
        return $isWebTest;
    }
}
