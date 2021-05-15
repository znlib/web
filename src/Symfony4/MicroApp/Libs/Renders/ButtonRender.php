<?php

namespace ZnLib\Web\Symfony4\MicroApp\Libs\Renders;

use ZnCore\Base\Legacy\Yii\Helpers\Html;

class ButtonRender extends BaseRender
{

    protected function defaultOptions(): array {
        return [
            'class' => "btn btn-primary btn-flat",
        ];
    }

    public function render()
    {
        $options = $this->getOptions();
        return Html::submitButton($this->getViewOption('label'), $options);
    }
}
