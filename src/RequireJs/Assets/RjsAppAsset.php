<?php

namespace ZnLib\Web\RequireJs\Assets;

use ZnLib\Web\Asset\Base\BaseAsset;
use ZnLib\Web\View\Libs\View;

class RjsAppAsset extends BaseAsset
{

    public function register(View $view)
    {
        (new \ZnLib\Web\Asset\Assets\Jquery3Asset())->cssFiles($view);
        (new \ZnLib\Web\TwBootstrap\Assets\Bootstrap4Asset())->cssFiles($view);
        (new \ZnLib\Web\Asset\Assets\PopperAsset())->cssFiles($view);
        (new \ZnLib\Web\Asset\Assets\Fontawesome5Asset())->register($view);
    }
}
