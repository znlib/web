<?php

namespace ZnLib\Web\Symfony4\MicroApp\Libs\Renders;

class NumberRender extends BaseInputRender
{

    public function defaultOptions(): array {
        return [
            'class'=>"form-control",
            'type' => 'number',
        ];
    }
}
