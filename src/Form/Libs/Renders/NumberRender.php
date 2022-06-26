<?php

namespace ZnLib\Web\Form\Libs\Renders;

class NumberRender extends BaseInputRender
{

    public function defaultOptions(): array {
        return [
            'class'=>"form-control",
            'type' => 'number',
        ];
    }
}
