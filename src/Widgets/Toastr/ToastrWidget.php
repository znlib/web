<?php

namespace ZnLib\Web\Widgets\Toastr;

use Illuminate\Support\Collection;
use Symfony\Component\PropertyAccess\PropertyAccess;
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
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        //dd($collection);
        foreach ($collection as $entity) {
            if($entity) {
                $type = $propertyAccessor->getValue($entity, 'type');
                $type = str_replace('alert-', '', $type);
                $content = $propertyAccessor->getValue($entity, 'content');
                $this->getView()->registerJs("toastr.{$type}('{$content}'); \n");
                //dd("toastr.{$type}('{$content}'); \n");
            }

        }
        $this->toastrService->clear();
    }
}
