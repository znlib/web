<?php

namespace ZnLib\Web\Widget\Widgets\Toastr;

use ZnBundle\Notify\Domain\Entities\ToastrEntity;
use ZnBundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use ZnCore\Collection\Interfaces\Enumerable;
use ZnLib\Web\View\Resources\Js;
use ZnLib\Web\Widget\Base\BaseWidget2;

class ToastrWidget extends BaseWidget2
{

    private $toastrService;

    public $closeButton = false;
    public $debug = false;
    public $newestOnTop = false;
    public $progressBar = true;
    public $positionClass = "toast-bottom-left";
    public $preventDuplicates = false;
    public $onclick = null;
    public $showDuration = "300";
    public $hideDuration = "1000";
    public $timeOut = "5000";
    public $extendedTimeOut = "1000";
    public $showEasing = "swing";
    public $hideEasing = "linear";
    public $showMethod = "fadeIn";
    public $hideMethod = "fadeOut";

    private $js;

    public function __construct(ToastrServiceInterface $toastrService, Js $js)
    {
        $this->toastrService = $toastrService;
        $this->js = $js;
    }

    public function assets(): array
    {
        return [
            ToastrAsset::class,
        ];
    }

    public function run(): string
    {
        $this->registerAssets();
        $collection = $this->toastrService->findAll();
        $this->generateHtml($collection);
        return '';
    }

    protected function registerAssets()
    {
        parent::registerAssets();
        $this->js->registerVar('toastr.options', $this);
    }

    private function generateHtml(Enumerable $collection)
    {
        if ($collection->isEmpty()) {
            return;
        }
//        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        /** @var ToastrEntity $entity */
        foreach ($collection as $entity) {
            $type = $entity->getType();
            $type = str_replace('alert-', '', $type);
            $content = $entity->getContent();
            $content = str_replace([PHP_EOL, '"'], ['\n', '\"'], $content);
            $this->js->registerCode("toastr.{$type}(\"{$content}\"); \n");
        }
        $this->toastrService->clear();
    }
}
