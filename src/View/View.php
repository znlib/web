<?php

namespace ZnLib\Web\View;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Libs\I18Next\Facades\I18Next;
use ZnCore\Base\Libs\I18Next\Interfaces\Services\TranslationServiceInterface;

class View
{

    private $jsCode = '';
    private $cssCode = '';
    private $cssFiles = [];
    private $jsFiles = [];
    private $renderDirectory;
    private $attributes = [];
    private $urlGenerator;
    private $translationService;

    public function __construct(UrlGeneratorInterface $urlGenerator, TranslationServiceInterface $translationService)
    {
        $this->urlGenerator = $urlGenerator;
        $this->translationService = $translationService;
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
        $this->cssFiles[] = [
            'file' => $file,
            'options' => $options,
        ];
    }

    public function getCssFiles(): array
    {
        return $this->cssFiles;
    }

    public function resetCssFiles()
    {
        $this->cssFiles = [];
    }

    public function registerCss(string $code) {
        $this->cssCode .= PHP_EOL . $code . PHP_EOL;
    }

    public function getCssCode(): string
    {
        return $this->cssCode;
    }

    public function resetCssCode()
    {
        $this->cssCode = '';
    }

    public function registerJsFile(string $file, array $options = []) {
        $this->jsFiles[] = [
            'file' => $file,
            'options' => $options,
        ];
    }

    public function getJsFiles(): array
    {
        return $this->jsFiles;
    }

    public function resetJsFiles()
    {
        $this->jsFiles = [];
    }

    public function registerJs(string $code) {
        $this->jsCode .= PHP_EOL . $code . PHP_EOL;
    }

    public function registerJsVar(string $name, $value) {
        if(is_object($value)) {
            $value = ArrayHelper::toArray($value);
        }
        $json = json_encode($value);
        $code = "$name = ".$json.";";
        $this->registerJs($code);
    }

    public function getJsCode(): string
    {
        return $this->jsCode;
    }

    public function resetJsCode()
    {
        $this->jsCode = '';
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
        include $viewFile;
    }
}
