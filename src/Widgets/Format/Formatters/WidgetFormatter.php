<?php

namespace ZnLib\Web\Widgets\Format\Formatters;

use ZnCore\Base\Helpers\ClassHelper;
use ZnCore\Base\Helpers\PhpHelper;

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
