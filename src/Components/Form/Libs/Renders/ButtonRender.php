<?php

namespace ZnLib\Web\Components\Form\Libs\Renders;

use ZnLib\Web\Components\Html\Helpers\Html;

class ButtonRender extends BaseInputRender
{

    public function defaultOptions(): array {
        return [
            'class' => "btn btn-primary",
        ];
    }

    public function render(): string
    {
        $options = $this->options();
        return Html::submitButton($this->getViewOption('label'), $options);
    }
}
