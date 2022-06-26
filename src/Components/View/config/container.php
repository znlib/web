<?php

use ZnLib\Web\Components\View\Libs\View;
use ZnLib\Web\Components\View\Resources\Css;
use ZnLib\Web\Components\View\Resources\Js;

return [
    'singletons' => [
        View::class => View::class,
        Css::class => Css::class,
        Js::class => Js::class,
    ],
];
