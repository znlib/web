<?php

namespace ZnLib\Web\Symfony4\MicroApp\Widgets\Layout;

use ZnLib\Web\Widgets\Base\BaseWidget2;

class ScriptWidget extends BaseWidget2
{
    
    public function run(): string
    {
        return $this->getView()->renderFile(__DIR__ . '/views/script.php');
    }
}
