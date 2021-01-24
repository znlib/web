<?php

namespace ZnLib\Web\Widgets\Base;

use ReflectionClass;
use ZnCore\Base\Helpers\ClassHelper;
use ZnCore\Base\Helpers\StringHelper;
use ZnLib\Web\View\View;
use ZnLib\Web\Widgets\Interfaces\WidgetInterface2;

abstract class BaseWidget2 implements WidgetInterface2
{

    abstract public function run(): string;

    public static function widget(array $config = []): string
    {
        $config['class'] = get_called_class();
        $instance = ClassHelper::createObject($config);
        return $instance->run();
    }

    protected function renderTemplate(string $templateCode, array $params)
    {
        return StringHelper::renderTemplate($templateCode, $params);
    }

    protected function render(string $relativeViewFileAlias, array $params)
    {
        $view = new View();
        $viewFile = $view->getRenderFile($this, $relativeViewFileAlias);
        return $this->renderFile($viewFile, $params);
    }

    protected function renderFile(string $viewFile, array $params)
    {
        $view = new View();
        return $view->getRenderContent($viewFile, $params);
    }
}
