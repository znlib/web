<?php

namespace ZnLib\Web\View\Assets;

use ZnLib\Web\View\View;

class BaseAsset implements AssetInterface
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
