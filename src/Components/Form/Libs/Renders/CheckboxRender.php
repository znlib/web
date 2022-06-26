<?php

namespace ZnLib\Web\Components\Form\Libs\Renders;

use ZnLib\Web\Helpers\Html;

class CheckboxRender extends BaseInputRender
{

    public function defaultOptions(): array {
        return [
            'type' => 'checkbox',
        ];
    }

    public function render(): string
    {
        $options = $this->options();
        $value = $this->getViewOptions()['data'];
        if ($value) {
            $options['checked'] = 'checked';
        }
        $labelHtml = $this->getViewOption('label');
        $input = Html::tag('input', '', $options) /*. $labelHtml*/;
        /*$label = Html::tag('label', null, [
            'for' => $this->getViewOption('id'),
        ]);*/
        return $input;
    }
}
