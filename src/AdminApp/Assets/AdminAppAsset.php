<?php

namespace ZnLib\Web\AdminApp\Assets;

use ZnLib\Web\Asset\Base\BaseAsset;
use ZnLib\Web\View\Libs\View;

class AdminAppAsset extends BaseAsset
{

    public function register(View $view)
    {
        (new \ZnLib\Web\Asset\Assets\Jquery3Asset())->register($view);
        (new \ZnLib\Web\TwBootstrap\Assets\Bootstrap4Asset())->register($view);
        (new \ZnLib\Web\AdminLte3\Assets\AdminLte3Asset())->register($view);
        (new \ZnLib\Web\Asset\Assets\PopperAsset())->register($view);
        (new \ZnLib\Web\Asset\Assets\Fontawesome5Asset())->register($view);
    }
}
