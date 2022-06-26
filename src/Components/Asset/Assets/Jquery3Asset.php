<?php

namespace ZnLib\Web\Components\Asset\Assets;

use ZnLib\Web\Components\Asset\Base\BaseAsset;
use ZnLib\Web\Components\View\Libs\View;

class Jquery3Asset extends BaseAsset
{

    public function jsFiles(View $view)
    {
        $view->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js', [
            'integrity' => 'sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==',
            'crossorigin' => 'anonymous',
            'referrerpolicy' => 'no-referrer',
        ]);
    }

    public function cssFiles(View $view)
    {
        
    }
}
