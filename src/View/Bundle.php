<?php

namespace ZnLib\Web\View;

use ZnCore\Base\Bundle\Base\BaseBundle;

class Bundle extends BaseBundle
{

    public function container(): array
    {
        return [
            __DIR__ . '/config/container.php',
        ];
    }
}