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
        $options = $this->getOptions();
        $input = Html::tag('input', '', $options) . $this->getViewOption('label');
        return Html::tag('label', $input, [
            'for' => $this->getViewOption('id'),
        ]);
    }
}
