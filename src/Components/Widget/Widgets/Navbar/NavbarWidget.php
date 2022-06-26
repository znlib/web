<?php

namespace ZnLib\Web\Components\Widget\Widgets\Navbar;

use ZnLib\Web\Components\Widget\Base\BaseWidget2;

class NavbarWidget extends BaseWidget2
{

    public $leftMenu;
    public $rightMenu;

    public function run(): string
    {
        return $this->render('index', [
            'leftMenu' => $this->leftMenu,
            'rightMenu' => $this->rightMenu,
        ]);
    }
}