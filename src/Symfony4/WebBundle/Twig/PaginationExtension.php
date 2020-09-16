<?php

namespace ZnLib\Web\Symfony4\WebBundle\Twig;

use ZnCore\Domain\Entities\DataProviderEntity;
use ZnLib\Web\Widgets\PaginationWidget;
use Symfony\Component\HttpFoundation\Request;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PaginationExtension extends AbstractExtension
{

    public function getFunctions()
    {
        return [
            new TwigFunction('pagination', [$this, 'pagination'], ['is_safe' => ['html']]),
        ];
    }

    public function pagination(DataProviderEntity $dataProviderEntity, Request $request)
    {
        $widgetInstance = new PaginationWidget($dataProviderEntity, $request);
        return $widgetInstance->render();
    }

}
