<?php

namespace ZnLib\Web\Components\Asset\Assets;

use ZnLib\Web\Components\Asset\Base\BaseAsset;
use ZnLib\Web\Components\View\Libs\View;

class AjaxLoaderAsset extends BaseAsset
{

    public function jsFiles(View $view)
    {
        if (!empty($_ENV['AJAX_ENABLE'])) {
            $view->registerJsFile('/assets/app/lib/ajax.js');
            $view->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js', [
                'integrity' => "sha512-YUkaLm+KJ5lQXDBdqBqk7EVhJAdxRnVdT2vtCzwPHSweCzyMgYV/tgGF4/dCyqtCC2eCphz0lRQgatGVdfR0ww==",
                'crossorigin' => "anonymous",
                'referrerpolicy' => "no-referrer"
            ]);
            $view->getJs()->registerVar('ajaxLoaderStartTime', $_ENV['AJAX_LOADER_START_TIME'] ?? null);
        }
    }

    public function cssFiles(View $view)
    {

    }
}
