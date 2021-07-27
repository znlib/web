<?php

namespace ZnLib\Web\Symfony4\MicroApp\Libs\Renders;

class HiddenRender extends BaseInputRender
{

    public function defaultOptions(): array {
        return [
            'class'=>"form-control",
            'type' => 'hidden',
        ];
    }
}
