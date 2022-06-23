<?php

namespace ZnLib\Web\Widgets\Format\Formatters;

use ZnCore\Base\I18Next\Facades\I18Next;

class UnknownTypeFormatter extends BaseFormatter implements FormatterInterface
{

    public $label;

    public function render($items)
    {
        return $this->label ?? I18Next::t('core', 'main.unknown_type');
    }
}
