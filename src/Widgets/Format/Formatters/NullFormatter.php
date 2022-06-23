<?php

namespace ZnLib\Web\Widgets\Format\Formatters;

use ZnCore\Base\I18Next\Facades\I18Next;

class NullFormatter extends BaseFormatter implements FormatterInterface
{

    public function render($items)
    {
        return '<i class="text-muted">' . I18Next::t('core', 'main.empty') . '</i>';
    }
}
