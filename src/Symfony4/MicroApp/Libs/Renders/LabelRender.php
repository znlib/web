<?php

namespace ZnLib\Web\Symfony4\MicroApp\Libs\Renders;

use ZnCore\Base\Legacy\Yii\Helpers\Html;

class LabelRender extends BaseRender
{

    protected function tagName(): string
    {
        return 'label';
    }

    protected function defaultOptions(): array {
        return [
            'class'=>"control-label",
            'for' => $this->getViewOption('id'),
        ];
    }

    public function render()
    {
        $options = $this->defaultOptions();
        return Html::tag('label', $this->getViewOption('label'), $options);
    }
}
