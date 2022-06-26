<?php

namespace ZnLib\Web\Components\Widget\Widgets\Format\Formatters;

class StringFormatter extends BaseFormatter implements FormatterInterface
{

    public function render($value)
    {
        return htmlspecialchars($value);
    }
}
