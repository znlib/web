<?php

namespace ZnLib\Web\Components\Asset\Assets;

use ZnLib\Web\Components\Asset\Base\BaseAsset;
use ZnLib\Web\Components\View\Libs\View;

class RjsAppAsset extends BaseAsset
{

    public function register(View $view)
    {
        (new \ZnLib\Web\Components\Asset\Assets\Jquery3Asset())->cssFiles($view);
        (new \ZnLib\Web\Components\Asset\Assets\Bootstrap4Asset())->cssFiles($view);
        (new \ZnLib\Web\Components\Asset\Assets\PopperAsset())->cssFiles($view);
        (new \ZnLib\Web\Components\Asset\Assets\Fontawesome5Asset())->register($view);
    }
}
