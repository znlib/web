<?php

namespace ZnLib\Web\Symfony4\MicroApp\Libs\Renders;

class TextRender extends BaseRender
{

    protected function defaultOptions(): array {
        return [
            'class'=>"form-control"
        ];
    }
}
