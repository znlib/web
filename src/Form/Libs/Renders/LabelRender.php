<?php

namespace ZnLib\Web\Form\Libs\Renders;

use ZnLib\Web\Html\Helpers\Html;

class LabelRender extends BaseInputRender
{

    public function tagName(): string
    {
        return 'label';
    }

    public function defaultOptions(): array {
        return [
            'class'=>"control-label",
            'for' => $this->getViewOption('id'),
        ];
    }

    public function render(): string
    {
        $options = $this->defaultOptions();
        return Html::tag('label', $this->getViewOption('label'), $options);
    }
}
