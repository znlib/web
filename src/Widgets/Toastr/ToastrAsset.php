<?php

namespace ZnLib\Web\Widgets\Toastr;

use ZnLib\Web\View\Assets\BaseAsset;
use ZnLib\Web\View\View;

class ToastrAsset extends BaseAsset
{

    public function jsFiles(View $view)
    {
        $view->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js');
        $view->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css');
    }
}
