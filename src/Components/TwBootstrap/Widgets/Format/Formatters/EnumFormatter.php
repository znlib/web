<?php

namespace ZnLib\Web\Components\TwBootstrap\Widgets\Format\Formatters;

use ZnCore\Base\Enum\Helpers\EnumHelper;

class EnumFormatter extends BaseFormatter implements FormatterInterface
{

    public $enumClass;

    public function render($value)
    {
        return EnumHelper::getLabel($this->enumClass, $value);
    }
}
