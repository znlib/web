<?php

namespace ZnLib\Web\Components\Asset\Assets;

use ZnLib\Web\Components\Asset\Base\BaseAsset;
use ZnLib\Web\Components\View\Libs\View;

class Bootstrap4Asset extends BaseAsset
{

    public function jsFiles(View $view)
    {
        $view->registerJsFile('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js', [
            'integrity' => 'sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl',
            'crossorigin' => 'anonymous',
        ]);
    }

    public function cssFiles(View $view)
    {
        $view->registerCssFile('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css', [
            'integrity' => 'sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm',
            'crossorigin' => 'anonymous',
        ]);
    }
}
