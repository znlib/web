<?php

namespace ZnLib\Web\View;

use ReflectionClass;

class View
{

    private $viewPath = 'views';

    public function getViewPath(): string
    {
        return $this->viewPath;
    }

    public function setViewPath(string $viewPath): void
    {
        $this->viewPath = $viewPath;
    }

    public function getRenderContent(string $viewFile, array $__params): string
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

    public function getRenderFile(object $class, string $relativeViewFileAlias): string
    {
        $reflector = new ReflectionClass($class);
        $classFile = $reflector->getFileName();
        $classDirectory = dirname($classFile);
        $viewsDirectory = $classDirectory . DIRECTORY_SEPARATOR . $this->viewPath;
        $viewFile = $viewsDirectory . DIRECTORY_SEPARATOR . $relativeViewFileAlias;
        return $viewFile . '.php';
    }

    protected function includeRender(string $viewFile, array $__params)
    {
        extract($__params);
        include $viewFile;
    }
}
