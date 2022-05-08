<?php

namespace ZnLib\Web\Symfony4\WebBundle\Twig;

use Psr\Container\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use ZnLib\Web\Widgets\Interfaces\WidgetInterface;

class WidgetExtension extends AbstractExtension
{

    private $items = [];
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('widget', [$this, 'widget'], ['is_safe' => ['html']]),
        ];
    }

    public function widget(string $widgetClass, array $params = [])
    {
        /** @var WidgetInterface $widget */
//        $widget = DiHelper::make($widgetClass, $this->container);
        $widget = $this->container->get($widgetClass);
        foreach ($params as $paramName => $paramValue) {
            $widget->{$paramName} = $paramValue;
        }
        return $widget->render();
    }

}
