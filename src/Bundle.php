<?php

namespace ZnLib\Web;

use ZnCore\Base\Bundle\Base\BaseBundle;

class Bundle extends BaseBundle
{

    public function i18next(): array
    {
        return [
            'web' => 'vendor/znlib/web/src/i18next/__lng__/__ns__.json',
        ];
    }
}
