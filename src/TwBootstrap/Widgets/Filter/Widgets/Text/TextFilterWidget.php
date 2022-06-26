<?php

namespace ZnLib\Web\TwBootstrap\Widgets\Filter\Widgets\Text;

use ZnLib\Web\TwBootstrap\Widgets\Filter\Widgets\BaseFilterWidget;

class TextFilterWidget extends BaseFilterWidget
{

    public $type = 'text';
    public $options = [
        'class' => 'form-control',
        'onkeydown' => 'filterForm.submitOnKeyDown(this, event)',
    ];
}