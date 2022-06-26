<?php

namespace ZnLib\Web\WebTranslate;

use ZnCore\Base\Bundle\Base\BaseBundle;

class Bundle extends BaseBundle
{

    public function i18next(): array
    {
        return [
            'web' => 'vendor/znlib/web/src/WebTranslate/i18next/__lng__/__ns__.json',
        ];
    }
}
