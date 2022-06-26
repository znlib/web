<?php

namespace ZnLib\Web\Components\Widget\Widgets\Format\Formatters;

use ZnCore\Base\Instance\Helpers\ClassHelper;
use ZnCore\Base\Php\Helpers\PhpHelper;

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
