<?php

namespace ZnLib\Web\Widgets\Base;

use ReflectionClass;
use ZnCore\Base\Helpers\ClassHelper;
use ZnCore\Base\Helpers\StringHelper;

abstract class BaseWidget2 //implements WidgetInterface
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

    protected function render(string $templateFile, array $params)
    {
        $viewFile = $this->getRenderFile($templateFile);
        return $this->getRenderContent($viewFile, $params);
    }

    protected function getRenderContent(string $viewFile, array $__params): string
    {
        $out = '';
        ob_start();
        ob_implicit_flush(false);
        try {
            $this->includeRender($viewFile, $__params);
            // after render wirte in $out
        } catch (\Exception $e) {
            // close the output buffer opened above if it has not been closed already
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            throw $e;
        }
        return ob_get_clean() . $out;
    }

    protected function includeRender(string $viewFile, array $__params)
    {
        extract($__params);
        include $viewFile;
    }

    protected function getRenderFile(string $templateFile): string
    {
        $reflector = new ReflectionClass($this);
        $classFile = $reflector->getFileName();
        $classDirectory = dirname($classFile);
        $viewsDirectory = $classDirectory . DIRECTORY_SEPARATOR . 'views';
        $viewFile = $viewsDirectory . DIRECTORY_SEPARATOR . $templateFile;
        return $viewFile . '.php';
    }
}
