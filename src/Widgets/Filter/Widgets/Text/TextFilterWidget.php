<?php

namespace ZnLib\Web\Widgets\Filter\Widgets\Text;

use ZnLib\Web\Widgets\Filter\Widgets\BaseFilterWidget;

class TextFilterWidget extends BaseFilterWidget
{

    public $type = 'text';
    public $options = [
        'class' => 'form-control',
        'onkeydown' => 'submitForm(this, event)',
    ];
}