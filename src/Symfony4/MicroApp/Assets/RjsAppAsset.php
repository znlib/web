<?php

namespace ZnLib\Web\Symfony4\MicroApp\Assets;

use ZnLib\Web\View\Assets\BaseAsset;
use ZnLib\Web\View\View;

class RjsAppAsset extends BaseAsset
{

    public function register(View $view)
    {
        (new \ZnLib\Web\Symfony4\MicroApp\Assets\Jquery3Asset())->cssFiles($view);
        (new \ZnLib\Web\Symfony4\MicroApp\Assets\Bootstrap4Asset())->cssFiles($view);
        (new \ZnLib\Web\Symfony4\MicroApp\Assets\PopperAsset())->cssFiles($view);
        (new \ZnLib\Web\Symfony4\MicroApp\Assets\Fontawesome5Asset())->register($view);
    }
}
