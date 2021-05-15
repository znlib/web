<?php

namespace ZnLib\Web\Symfony4\MicroApp\Libs\Renders;

class HintRender extends BaseInputRender
{

    public function tagName(): string
    {
        return 'p';
    }

    public function defaultOptions(): array {
        return [
            'class'=>"help-block help-block-error",
        ];
    }
}
