<?php

namespace ZnLib\Web\WebApp;

use ZnCore\Bundle\Base\BaseBundle;

class Bundle extends BaseBundle
{

    public function deps(): array
    {
        return [
            \ZnLib\Web\Form\Bundle::class,
            \ZnLib\Web\View\Bundle::class,
            \ZnLib\Web\Layout\Bundle::class,
        ];
    }

    public function container(): array
    {
        return [
            __DIR__ . '/config/container.php',
        ];
    }
}
