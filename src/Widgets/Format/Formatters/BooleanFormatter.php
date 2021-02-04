<?php

namespace ZnLib\Web\Widgets\Format\Formatters;

use ZnCore\Base\Libs\I18Next\Facades\I18Next;

class BooleanFormatter extends BaseFormatter implements FormatterInterface
{

    public $yesLabel;
    public $noLabel;

    public function render($items)
    {
        $yesLabel = $this->yesLabel ?? I18Next::t('core', 'main.yes');
        $noLabel = $this->noLabel ?? I18Next::t('core', 'main.no');
        return $items ? $yesLabel : $noLabel;
    }
}
