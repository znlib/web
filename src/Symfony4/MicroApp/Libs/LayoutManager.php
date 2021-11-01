<?php

namespace ZnLib\Web\Symfony4\MicroApp\Libs;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use ZnBundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use ZnLib\Web\Widgets\BreadcrumbWidget;

class LayoutManager
{

    protected $toastrService;
    protected $breadcrumbWidget;
    protected $urlGenerator;

    public function __construct(
        ToastrServiceInterface $toastrService,
        BreadcrumbWidget $breadcrumbWidget,
        UrlGeneratorInterface $urlGenerator
    )
    {
        $this->setToastrService($toastrService);
        $this->setBreadcrumbWidget($breadcrumbWidget);
        $this->urlGenerator = $urlGenerator;
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

    public function addBreadcrumb(string $label, string $name, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): void
    {
        $url = $this->urlGenerator->generate($name, $parameters, $referenceType);
        $this->getBreadcrumbWidget()->add($label, $url);
    }

    public function toastrSuccess($message, int $delay = null): void
    {
        $this->getToastrService()->success($message, $delay);
    }

    public function toastrInfo($message, int $delay = null) {
        $this->getToastrService()->info($message, $delay);
    }

    public function toastrWarning($message, int $delay = null) {
        $this->getToastrService()->warning($message, $delay);
    }

    public function toastrError($message, int $delay = null) {
        $this->getToastrService()->error($message, $delay);
    }
}
