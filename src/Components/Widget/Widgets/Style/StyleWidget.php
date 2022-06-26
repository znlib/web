<?php

namespace ZnLib\Web\Components\Widget\Widgets\Style;

use ZnLib\Web\Components\View\Resources\Css;
use ZnLib\Web\Components\Widget\Base\BaseWidget2;

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
