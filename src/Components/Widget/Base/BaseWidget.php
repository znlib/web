<?php

namespace ZnLib\Web\Components\Widget\Base;

use ZnCore\Base\Instance\Helpers\ClassHelper;

use ZnCore\Base\Text\Helpers\TemplateHelper;
use ZnLib\Web\Components\Widget\Interfaces\WidgetInterface;

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