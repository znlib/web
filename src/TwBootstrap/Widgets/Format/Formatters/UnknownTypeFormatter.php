<?php

namespace ZnLib\Web\TwBootstrap\Widgets\Format\Formatters;

use ZnLib\I18Next\Facades\I18Next;

class UnknownTypeFormatter extends BaseFormatter implements FormatterInterface
{

    public $label;

    public function render($items)
    {
        return $this->label ?? I18Next::t('core', 'main.unknown_type');
    }
}
