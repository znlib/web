<?php

namespace ZnLib\Web\Widgets\Filter\Widgets;

use ZnCore\Base\Legacy\Yii\Helpers\Html;
use ZnLib\Web\Widgets\Base\BaseWidget2;

class BaseFilterWidget extends BaseWidget2
{

    public $type;
    public $name;
    public $value;
    public $options = [
        'class' => 'form-control',
    ];

    public function run(): string
    {
        $name = 'filter[' . $this->name . ']';
        return Html::input($this->type, $name, $this->value, $this->options);
    }
}