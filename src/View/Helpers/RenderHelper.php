<?php

namespace ZnLib\Web\View\Helpers;

use ReflectionClass;

class RenderHelper
{

    public static function getRenderDirectoryByClass(object $class, string $viewPath = 'views'): string
    {
        $reflector = new ReflectionClass($class);
        $classFile = $reflector->getFileName();
        $classDirectory = dirname($classFile);
        $viewsDirectory = $classDirectory . DIRECTORY_SEPARATOR . $viewPath;
        return $viewsDirectory;
    }
}
