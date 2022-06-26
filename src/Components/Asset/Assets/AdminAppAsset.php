<?php

namespace ZnLib\Web\Components\Asset\Assets;

use ZnLib\Web\Components\Asset\Base\BaseAsset;
use ZnLib\Web\Components\View\Libs\View;

class AdminAppAsset extends BaseAsset
{

    public function register(View $view)
    {
        (new \ZnLib\Web\Components\Asset\Assets\Jquery3Asset())->register($view);
        (new \ZnLib\Web\Components\TwBootstrap\Assets\Bootstrap4Asset())->register($view);
        (new \ZnLib\Web\Components\AdminLte3\Assets\AdminLte3Asset())->register($view);
        (new \ZnLib\Web\Components\Asset\Assets\PopperAsset())->register($view);
        (new \ZnLib\Web\Components\Asset\Assets\Fontawesome5Asset())->register($view);
    }
}
