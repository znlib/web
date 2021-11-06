<?php

namespace ZnLib\Web\Symfony4\MicroApp\Widgets\Layout;

use ZnLib\Web\View\Resources\Css;
use ZnLib\Web\Widgets\Base\BaseWidget2;

class StyleWidget extends BaseWidget2
{

    private $css;

    public function __construct(Css $css)
    {
        $this->css = $css;
    }

    public function run(): string
    {
        return $this->getView()->renderFile(__DIR__ . '/views/style.php', [
            'css' => $this->css,
        ]);
    }
}
