<?php

namespace ZnLib\Web\Symfony4\MicroApp\Libs;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use ZnBundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use ZnLib\Rpc\Domain\Libs\RpcClient;
use ZnLib\Web\Symfony4\MicroApp\Traits\ControllerFormTrait;
use ZnLib\Web\Widgets\BreadcrumbWidget;
use ZnSandbox\Sandbox\Rpc\Domain\Interfaces\Services\MethodServiceInterface;
use ZnSandbox\Sandbox\RpcClient\Domain\Interfaces\Services\ClientServiceInterface;
use ZnSandbox\Sandbox\RpcClient\Domain\Interfaces\Services\FavoriteServiceInterface;

class layoutManager
{

   // use ControllerFormTrait;

    protected $toastrService;
    protected $breadcrumbWidget;

    public function __construct(
        ToastrServiceInterface $toastrService,
        BreadcrumbWidget $breadcrumbWidget
        //UrlGeneratorInterface $urlGenerator
    )
    {
        $this->setToastrService($toastrService);
        $this->setBreadcrumbWidget($breadcrumbWidget);
    }

    public function getToastrService(): ToastrServiceInterface
    {
        return $this->toastrService;
    }

    public function setToastrService(ToastrServiceInterface $toastrService): void
    {
        $this->toastrService = $toastrService;
    }

    public function getBreadcrumbWidget(): BreadcrumbWidget
    {
        return $this->breadcrumbWidget;
    }

    public function setBreadcrumbWidget(BreadcrumbWidget $breadcrumbWidget): void
    {
        $this->breadcrumbWidget = $breadcrumbWidget;
    }
}
