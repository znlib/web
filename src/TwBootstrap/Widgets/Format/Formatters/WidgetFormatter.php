<?php

namespace ZnLib\Web\TwBootstrap\Widgets\Format\Formatters;

use ZnCore\Instance\Helpers\ClassHelper;
use ZnCore\Code\Helpers\PhpHelper;

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
