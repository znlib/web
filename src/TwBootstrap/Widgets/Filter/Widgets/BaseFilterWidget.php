<?php

namespace ZnLib\Web\TwBootstrap\Widgets\Filter\Widgets;

use ZnLib\Web\Html\Helpers\Html;
use ZnLib\Web\Widget\Base\BaseWidget2;

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