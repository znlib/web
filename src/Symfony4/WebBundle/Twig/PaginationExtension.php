<?php

namespace ZnLib\Web\Symfony4\WebBundle\Twig;

use Symfony\Component\HttpFoundation\Request;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use ZnCore\Domain\Entities\DataProviderEntity;

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
//        $widgetInstance = new PaginationWidget($dataProviderEntity, $request);
//        return $widgetInstance->render();
    }

}
