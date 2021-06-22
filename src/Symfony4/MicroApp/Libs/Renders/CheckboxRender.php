<?php

namespace ZnLib\Web\Symfony4\MicroApp\Libs\Renders;

use ZnCore\Base\Legacy\Yii\Helpers\Html;

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
        $value = $this->getViewOptions()['value'];
        if ($value) {
            $options['checked'] = 'checked';
        }
        $labelHtml = $this->getViewOption('label');
        $input = Html::tag('input', '', $options) /*. $labelHtml*/;
        return Html::tag('label', $input, [
            'for' => $this->getViewOption('id'),
        ]);
    }
}
