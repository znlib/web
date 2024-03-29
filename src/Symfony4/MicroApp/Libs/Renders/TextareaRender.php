<?php

namespace ZnLib\Web\Symfony4\MicroApp\Libs\Renders;

use ZnCore\Base\Legacy\Yii\Helpers\Html;

class TextareaRender extends BaseInputRender
{

    public function tagName(): string
    {
        return 'textarea';
    }

    public function defaultOptions(): array {
        return [
            'class'=>"form-control"
        ];
    }

    public function render(): string
    {
        $options = $this->options();
        //$options = $this->defaultOptions();
//        dd($this->getViewOptions());
        unset($options['value']);
        return Html::textarea($this->getViewOptions()['full_name'], $this->getViewOptions()['value'], $options);
    }
}
