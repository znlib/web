<?php

namespace ZnLib\Web\Components\Form\Libs\Renders;

class PasswordRender extends BaseInputRender
{

    public function defaultOptions(): array {
        return [
            'class'=>"form-control",
            'type' => 'password',
        ];
    }
}
