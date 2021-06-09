<?php

namespace ZnLib\Web\Symfony4\MicroApp\Libs\Renders;

use ZnCore\Base\Legacy\Yii\Helpers\Html;

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
