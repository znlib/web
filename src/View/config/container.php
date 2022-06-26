<?php

use ZnLib\Web\View\Libs\View;
use ZnLib\Web\View\Resources\Css;
use ZnLib\Web\View\Resources\Js;

return [
    'singletons' => [
        View::class => View::class,
        Css::class => Css::class,
        Js::class => Js::class,
    ],
];
