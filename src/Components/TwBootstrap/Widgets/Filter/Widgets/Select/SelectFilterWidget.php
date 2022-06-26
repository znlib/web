<?php

namespace ZnLib\Web\Components\TwBootstrap\Widgets\Filter\Widgets\Select;

use ZnLib\Web\Components\TwBootstrap\Widgets\Filter\Widgets\BaseFilterWidget;
use ZnLib\Web\Components\Html\Helpers\Html;

class SelectFilterWidget extends BaseFilterWidget
{

    public $options = [
        'class' => 'form-control',
        'onchange' => 'filterForm.submit()',
    ];
    public $choices = [];

    public function run(): string
    {
        $name = 'filter[' . $this->name . ']';
        return Html::dropDownList($name, $this->value, $this->choices, $this->options);
    }
}