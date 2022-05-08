<?php

namespace ZnLib\Web\Symfony4\WebBundle\Twig;

use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use ZnCore\Base\Libs\FileSystem\Helpers\FilePathHelper;

class AssetExtension extends AbstractExtension
{

    private $style = [];
    private $script = [];
    private $types = [
        'css' => 'style',
        'js' => 'script',
    ];
    private $resources = [];

    public function getFunctions()
    {
        return [
            new TwigFunction('asset', [$this, 'asset'], ['is_safe' => ['html']]),
            new TwigFunction('style', [$this, 'style'], ['is_safe' => ['html']]),
            new TwigFunction('script', [$this, 'script'], ['is_safe' => ['html']]),
            new TwigFunction('resourceList', [$this, 'resourceList'], ['is_safe' => ['html']]),
            new TwigFunction('resource', [$this, 'includeResource'], ['is_safe' => ['html']]),
        ];
    }

    public function asset(string $path)
    {
        return $path;
    }

    public function includeResource(string $path, string $type = null, array $attributes = [])
    {
        if (empty($type)) {
            $urlInfo = parse_url($path);
            $extension = FilePathHelper::fileExt($urlInfo['path']);
            $type = $this->types[$extension];
        }
        $this->{$type}[] = [
            'path' => $path,
            'attributes' => $attributes,
        ];
    }

    public function script(string $path, array $attributes = [])
    {
        $this->script[] = [
            'path' => $path,
            'attributes' => $attributes,
        ];
    }

    public function style(string $path, array $attributes = [])
    {
        $this->style[] = [
            'path' => $path,
            'attributes' => $attributes,
        ];
    }

    public function resourceList(string $name)
    {
        return $this->{$name};
    }

}
