<?php

namespace ZnLib\Web\Widget\Widgets\Toastr;

use ZnLib\Web\Asset\Base\BaseAsset;
use ZnLib\Web\View\Libs\View;

class ToastrAsset extends BaseAsset
{

    public function jsFiles(View $view)
    {
        $view->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js');
        $view->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css');
    }
}
