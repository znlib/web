<?php

namespace ZnLib\Web\TwBootstrap\Widgets\Format\Formatters;

use ZnCore\Enum\Helpers\EnumHelper;

class EnumFormatter extends BaseFormatter implements FormatterInterface
{

    public $enumClass;

    public function render($value)
    {
        return EnumHelper::getLabel($this->enumClass, $value);
    }
}
