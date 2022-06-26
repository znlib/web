<?php

namespace ZnLib\Web\WebApp\Assets;

use ZnLib\Web\Asset\Base\BaseAsset;
use ZnLib\Web\View\Libs\View;

class AppAsset extends BaseAsset
{

    public function register(View $view)
    {
        (new \ZnLib\Web\Asset\Assets\Jquery3Asset())->register($view);
        (new \ZnLib\Web\Asset\Assets\AjaxLoaderAsset())->register($view);
        (new \ZnLib\Web\TwBootstrap\Assets\Bootstrap4Asset())->register($view);
        (new \ZnLib\Web\Asset\Assets\PopperAsset())->register($view);
        (new \ZnLib\Web\Asset\Assets\Fontawesome5Asset())->register($view);
    }
}
