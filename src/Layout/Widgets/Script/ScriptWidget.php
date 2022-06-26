<?php

namespace ZnLib\Web\Layout\Widgets\Script;

use ZnBundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use ZnLib\Web\View\Resources\Js;
use ZnLib\Web\Widget\Base\BaseWidget2;

class ScriptWidget extends BaseWidget2
{

    private $js;

    public function __construct(Js $js)
    {
        $this->js = $js;
    }

    public function run(): string
    {
        return $this->getView()->renderFile(__DIR__ . '/views/script.php', [
            'js' => $this->js,
        ]);
    }
}
