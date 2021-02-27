<?php

namespace ZnLib\Web\View;

use ReflectionClass;

class View
{

    public function getRenderDirectory(): string
    {
        return $this->renderDirectory;
    }

    public function setRenderDirectory(string $renderDirectory): void
    {
        $this->renderDirectory = $renderDirectory;
    }

    public function render(string $viewFile, array $__params): string
    {
        $file = $this->getRenderDirectory() . DIRECTORY_SEPARATOR . $viewFile . '.php';
        return $this->renderFile($file, $__params);
    }

    public function renderFile(string $viewFile, array $__params = []): string
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

    /*public function getRenderFile(object $class, string $relativeViewFileAlias): string
    {
        $viewsDirectory = $this->getRenderDirectory($class);
        $viewFile = $viewsDirectory . DIRECTORY_SEPARATOR . $relativeViewFileAlias;
        return $viewFile . '.php';
    }*/

    protected function includeRender(string $viewFile, array $__params = [])
    {
        extract($__params);
        include $viewFile;
    }
}
