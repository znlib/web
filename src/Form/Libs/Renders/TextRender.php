<?php

namespace ZnLib\Web\Form\Libs\Renders;

class TextRender extends BaseInputRender
{

    public function defaultOptions(): array {
        return [
            'class'=>"form-control"
        ];
    }
}
