<?php

namespace ZnLib\Web\Widgets\Toastr;

use Illuminate\Support\Collection;
use Symfony\Component\PropertyAccess\PropertyAccess;
use ZnBundle\Notify\Domain\Entities\ToastrEntity;
use ZnBundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use ZnCore\Base\Helpers\ClassHelper;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnLib\Web\Widgets\Base\BaseWidget2;

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

    public function __construct(ToastrServiceInterface $toastrService)
    {
        $this->toastrService = $toastrService;
    }

    public function run(): string
    {
        $collection = $this->toastrService->all();
        $this->generateHtml($collection);
        return '';
    }

    /*public static function create($content, $type = self::TYPE_SUCCESS, $delay = 5000)
    {
        self::getToastrService()->add($type, $content, $delay);
    }*/

    private function generateHtml(Collection $collection)
    {
        if ($collection->isEmpty()) {
            return;
        }
        $this->defineAsset();
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        /** @var ToastrEntity $entity */
        foreach ($collection as $entity) {
            //if($entity) {
            $type = $entity->getType();
            $type = str_replace('alert-', '', $type);
            $content = $entity->getContent();
            $this->getView()->registerJs("toastr.{$type}('{$content}'); \n");
            //}
        }
        $this->toastrService->clear();
    }

    private function defineAsset()
    {
        $this->getView()->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js');
        $this->getView()->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css');

        //$this->registerCssFile('/dist/toastr.min.css');
        //$this->registerJsFile('/dist/toastr.min.js');
        
        $this->getView()->registerJsVar('toastr.options', $this);
    }
}
