<?php

namespace ZnLib\Web\Widgets\Format\Formatters;

use ZnCore\Base\Libs\Enum\Helpers\EnumHelper;

class EnumFormatter extends BaseFormatter implements FormatterInterface
{

    public $enumClass;

    public function render($value)
    {
        return EnumHelper::getLabel($this->enumClass, $value);
    }
}
