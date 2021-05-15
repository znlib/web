<?php

namespace ZnLib\Web\Symfony4\MicroApp\Libs\Renders;

class PasswordRender extends BaseRender
{

    protected function defaultOptions(): array {
        return [
            'class'=>"form-control",
            'type' => 'password',
        ];
    }
}
