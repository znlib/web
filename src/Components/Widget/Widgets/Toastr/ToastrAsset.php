<?php

namespace ZnLib\Web\Components\Widget\Widgets\Toastr;

use ZnLib\Web\Components\Asset\Base\BaseAsset;
use ZnLib\Web\Components\View\Libs\View;

class ToastrAsset extends BaseAsset
{

    public function jsFiles(View $view)
    {
        $view->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js');
        $view->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css');
    }
}
