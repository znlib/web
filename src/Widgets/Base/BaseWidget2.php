<?php

namespace ZnLib\Web\Widgets\Base;

use ReflectionClass;
use ZnCore\Base\Helpers\ClassHelper;
use ZnCore\Base\Helpers\StringHelper;
use ZnLib\Web\View\Helpers\RenderHelper;
use ZnLib\Web\View\View;
use ZnLib\Web\Widgets\Interfaces\WidgetInterface2;

abstract class BaseWidget2 implements WidgetInterface2
{
    
    private $view;

    public function getView(): View
    {
        if($this->view == null) {
            $this->view = new View();
        }
        return $this->view;
    }

    public function setView(View $view): void
    {
        $this->view = $view;
    }
    
    abstract public function run(): string;

    public static function widget(array $config = []): string
    {
        $config['class'] = get_called_class();
        $instance = ClassHelper::createObject($config);
        return $instance->run();
    }

    /*protected function renderTemplate(string $templateCode, array $params)
    {
        return StringHelper::renderTemplate($templateCode, $params);
    }*/

    public function render(string $relativeViewFileAlias, array $params = [])
    {
        $renderDirectory = RenderHelper::getRenderDirectoryByClass($this);
//        $this->getView()->setRenderDirectory($renderDirectory);
//        dd($renderDirectory . '/' . $relativeViewFileAlias);
        return $this->getView()->renderFile($renderDirectory . '/' . $relativeViewFileAlias . '.php', $params);
    }

    /*protected function renderFile(string $viewFile, array $params)
    {
        $view = new View();
        $params['widget'] = $this;
        return $view->getRenderContent($viewFile, $params);
    }*/
}
