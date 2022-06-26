<?php

namespace ZnLib\Web\Components\TwBootstrap\Widgets\Format\Formatters;

use ZnLib\Components\I18Next\Facades\I18Next;

class UnknownTypeFormatter extends BaseFormatter implements FormatterInterface
{

    public $label;

    public function render($items)
    {
        return $this->label ?? I18Next::t('core', 'main.unknown_type');
    }
}
