<?php

namespace ZnLib\Web\Symfony4\MicroApp\Libs\Renders;

class HintRender extends BaseRender
{

    protected function tagName(): string
    {
        return 'p';
    }

    protected function defaultOptions(): array {
        return [
            'class'=>"help-block help-block-error",
        ];
    }
}
