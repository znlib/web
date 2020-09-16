<?php

namespace ZnLib\Web\Symfony4\WebBundle\Twig;

use ZnLib\Web\Widgets\ModalWidget;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ModalExtension extends AbstractExtension
{

    public function getFunctions()
    {
        return [
            new TwigFunction('modal', [$this, 'modal'], ['is_safe' => ['html']]),
        ];
    }

    public function modal(string $tagId, string $header, string $body, string $footer)
    {
        $widgetInstance = new ModalWidget;
        $widgetInstance->tagId = $tagId;
        $widgetInstance->header = $header;
        $widgetInstance->body = $body;
        $widgetInstance->footer = $footer;
        return $widgetInstance->render();
    }

}
