<?php

namespace ZnLib\Web\View;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Libs\I18Next\Facades\I18Next;
use ZnCore\Base\Libs\I18Next\Interfaces\Services\TranslationServiceInterface;
use ZnLib\Web\View\Helpers\RenderHelper;
use ZnLib\Web\View\Resources\Css;
use ZnLib\Web\View\Resources\Js;

class View
{

    private $js;
    private $css;

    private $renderDirectory;
    private $attributes = [];
    private $urlGenerator;
    private $translationService;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        TranslationServiceInterface $translationService,
        Js $js,
        Css $css
    )
    {
        $this->urlGenerator = $urlGenerator;
        $this->translationService = $translationService;
        $this->js = $js;
        $this->css = $css;
    }

    public function getJs(): Js
    {
        return $this->js;
    }

    public function getCss(): Css
    {
        return $this->css;
    }

    public function url(string $name, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH) {
        return $this->urlGenerator->generate($name, $parameters, $referenceType);
    }

    public function translate(string $bundleName, string $key, array $variables = []) {
        return $this->translationService->t($bundleName, $key, $variables);
    }

    public function addAttribute(string $name, $value) {
        $this->attributes[$name] = $value;
    }

    public function getAttribute(string $name, $default = null) {
        return $this->attributes[$name] ?? $default;
    }





    public function registerCssFile(string $file, array $options = []) {
        $this->css->registerFile($file, $options);
    }

    public function getCssFiles(): array
    {
        return $this->css->getFiles();
    }

    public function resetCssFiles()
    {
        $this->css->resetFiles();
    }

    public function registerCss(string $code) {
        $this->css->registerCode($code);
    }

    public function getCssCode(): string
    {
        return $this->css->getCode();
    }

    public function resetCssCode()
    {
        $this->css->resetCode();
    }





    public function registerJsFile(string $file, array $options = []) {
        $this->js->registerFile($file, $options);
    }

    public function getJsFiles(): array
    {
        return $this->js->getFiles();
    }

    public function resetJsFiles()
    {
        $this->js->getFiles();
    }

    public function registerJs(string $code) {
        $this->js->registerCode($code);
    }

    public function registerJsVar(string $name, $value) {
        $this->js->registerVar($name, $value);
    }

    public function getJsCode(): string
    {
        return $this->js->getCode();
    }

    public function resetJsCode()
    {
        $this->js->resetCode();
    }


    public function getRenderDirectory(): string
    {
        return $this->renderDirectory;
    }

    public function setRenderDirectory(string $renderDirectory): void
    {
        $this->renderDirectory = $renderDirectory;
    }

    public function render(string $viewFile, array $__params = []): string
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
        $view = $this;
        include $viewFile;
    }
}
