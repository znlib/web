<?php

namespace ZnLib\Web\Widgets\Format\Formatters;

use ZnCore\Base\Libs\Instance\Helpers\ClassHelper;
use ZnCore\Base\Libs\Php\Helpers\PhpHelper;

class WidgetFormatter extends BaseFormatter implements FormatterInterface
{

    public $widget;

    public function render($value)
    {
        $widget = PhpHelper::runValue($this->widget, [$this->attributeEntity->getEntity()]);
        $widget = ClassHelper::createObject($widget);
        return $widget->run();
    }
}
