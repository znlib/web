<?php

namespace ZnLib\Web\Symfony4\MicroApp\Assets;

use ZnLib\Web\View\Assets\BaseAsset;
use ZnLib\Web\View\View;

class AppAsset extends BaseAsset
{

    public function register(View $view)
    {
        (new \ZnLib\Web\Symfony4\MicroApp\Assets\Jquery3Asset())->register($view);
        (new \ZnLib\Web\Symfony4\MicroApp\Assets\AjaxLoaderAsset())->register($view);
        (new \ZnLib\Web\Symfony4\MicroApp\Assets\Bootstrap4Asset())->register($view);
        (new \ZnLib\Web\Symfony4\MicroApp\Assets\PopperAsset())->register($view);
        (new \ZnLib\Web\Symfony4\MicroApp\Assets\Fontawesome5Asset())->register($view);
    }
}
