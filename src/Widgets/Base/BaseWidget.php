<?php

namespace ZnLib\Web\Widgets\Base;

use ZnCore\Base\Helpers\StringHelper;
use ZnLib\Web\Widgets\Interfaces\WidgetInterface;

abstract class BaseWidget implements WidgetInterface
{

    abstract public function render(): string;

    protected function renderTemplate(string $templateCode, array $params)
    {
        return StringHelper::renderTemplate($templateCode, $params);
    }
}