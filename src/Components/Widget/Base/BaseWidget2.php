<?php

namespace ZnLib\Web\Components\Widget\Base;

use ReflectionClass;
use ZnCore\Base\Instance\Helpers\ClassHelper;

use ZnLib\Web\Components\View\Helpers\RenderHelper;
use ZnLib\Web\Components\View\Libs\View;
use ZnLib\Web\Components\Widget\Widgets\Interfaces\WidgetInterface2;

abstract class BaseWidget2 implements WidgetInterface2
{
    
    private $view;

    public function assets(): array
    {
        return [];
    }
    
    public function getView(): View
    {
        if($this->view == null) {
            $this->view = \ZnCore\Base\Container\Helpers\ContainerHelper::getContainer()->get(View::class);
            //$this->view = new View();
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
        /** @var self $instance */
        $instance = ClassHelper::createObject($config);
        return $instance->run();
    }

    protected function registerAssets() {
        $assets = $this->assets();
        foreach ($assets as $asset) {
            $assetInstance = ClassHelper::createInstance($asset);
            $assetInstance->register($this->getView());
        }
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
