<?php

namespace ZnLib\Web\Form\Libs\Renders;

class PasswordRender extends BaseInputRender
{

    public function defaultOptions(): array {
        return [
            'class'=>"form-control",
            'type' => 'password',
        ];
    }
}
