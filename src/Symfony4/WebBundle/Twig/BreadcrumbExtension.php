<?php

namespace ZnLib\Web\Symfony4\WebBundle\Twig;

use ZnLib\Web\Widgets\BreadcrumbWidget;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BreadcrumbExtension extends AbstractExtension
{

    private $items = [];

    public function getFunctions()
    {
        return [
            new TwigFunction('breadcrumb', [$this, 'breadcrumb'], ['is_safe' => ['html']]),
            new TwigFunction('breadcrumbAdd', [$this, 'breadcrumbAdd'], ['is_safe' => ['html']]),
            new TwigFunction('breadcrumbList', [$this, 'breadcrumbList'], ['is_safe' => ['html']]),
        ];
    }

    public function breadcrumb()
    {
        //dd($this->items);
        $widgetInstance = new BreadcrumbWidget($this->items);
        return $widgetInstance->render();
    }

    public function breadcrumbAdd(string $title, string $url = null)
    {
        //dd($title);
        $this->items[] = [
            'label' => $title,
            'url' => $url,
        ];
    }

    public function breadcrumbList()
    {
        return $this->items;
    }

}
