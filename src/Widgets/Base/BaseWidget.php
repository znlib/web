<?php

namespace ZnLib\Web\Widgets\Base;

use ZnCore\Base\Helpers\ClassHelper;
use ZnCore\Base\Libs\Text\Helpers\StringHelper;
use ZnCore\Base\Libs\Text\Helpers\TemplateHelper;
use ZnLib\Web\Widgets\Interfaces\WidgetInterface;

abstract class BaseWidget implements WidgetInterface
{

    abstract public function render(): string;

    public static function widget(array $options = []): string
    {
        $instance = ClassHelper::createObject(static::class, $options);
        return $instance->render();
    }

    protected function renderTemplate(string $templateCode, array $params)
    {
        return TemplateHelper::render($templateCode, $params);
    }
}