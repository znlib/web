<?php

namespace ZnLib\Web\Widgets\Toastr;

use Illuminate\Support\Collection;
use Symfony\Component\PropertyAccess\PropertyAccess;
use ZnBundle\Notify\Domain\Entities\ToastrEntity;
use ZnBundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use ZnLib\Web\Widgets\Base\BaseWidget2;

class ToastrWidget extends BaseWidget2
{
    /**
     * information alert
     */
    const TYPE_INFO = 'info';
    /**
     * danger/error alert
     */
    const TYPE_DANGER = 'danger';
    /**
     * success alert
     */
    const TYPE_SUCCESS = 'success';
    /**
     * warning alert
     */
    const TYPE_WARNING = 'warning';
    /**
     * primary alert
     */
    const TYPE_PRIMARY = 'primary';
    /**
     * default alert
     */
    const TYPE_DEFAULT = 'well';
    /**
     * custom alert
     */
    const TYPE_CUSTOM = 'custom';
    
    private $toastrService;

    public function __construct(ToastrServiceInterface $toastrService)
    {
        $this->toastrService = $toastrService;
    }

    public function run(): string
    {
        $collection = $this->toastrService->all();
        $this->generateHtml($collection);
        return $this->render('index', [
            //'collection' => $collection,
        ]);
    }

    /*public static function create($content, $type = self::TYPE_SUCCESS, $delay = 5000)
    {
        self::getToastrService()->add($type, $content, $delay);
    }*/

    private function generateHtml(Collection $collection)
    {
        if($collection->isEmpty()) {
            return;
        }

        $this->getView()->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js');
        $this->getView()->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css');

        $config = <<<JS

    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-bottom-left",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

JS;

        $this->getView()->registerJs($config);
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
}
