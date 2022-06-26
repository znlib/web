<?php

namespace ZnLib\Web\Components\Asset\Base;

use ZnLib\Web\Components\Asset\Interfaces\AssetInterface;
use ZnLib\Web\Components\View\Libs\View;

abstract class BaseAsset implements AssetInterface
{

    public function jsFiles(View $view)
    {

    }

    public function cssFiles(View $view)
    {

    }

    public function jsCode(View $view)
    {

    }

    public function cssCode(View $view)
    {

    }

    public function register(View $view)
    {
        $this->jsFiles($view);
        $this->cssFiles($view);
        $this->jsCode($view);
        $this->cssCode($view);
    }
}
