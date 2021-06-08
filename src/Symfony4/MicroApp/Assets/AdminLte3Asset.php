<?php

namespace ZnLib\Web\Symfony4\MicroApp\Assets;

use ZnLib\Web\View\Assets\BaseAsset;
use ZnLib\Web\View\View;

class AdminLte3Asset extends BaseAsset
{

    public function jsFiles(View $view)
    {
        $view->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js', [
            'integrity' => 'sha512-AJUWwfMxFuQLv1iPZOTZX0N/jTCIrLxyZjTRKQostNU71MzZTEPHjajSK20Kj1TwJELpP7gl+ShXw5brpnKwEg==',
            'crossorigin' => 'anonymous',
        ]);
    }

    public function cssFiles(View $view)
    {
        $view->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css', [
            'integrity' => 'sha512-mxrUXSjrxl8vm5GwafxcqTrEwO1/oBNU25l20GODsysHReZo4uhVISzAKzaABH6/tTfAxZrY2FprmeAP5UZY8A==',
            'crossorigin' => 'anonymous',
        ]);
    }
}
