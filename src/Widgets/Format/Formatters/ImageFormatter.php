<?php

namespace ZnLib\Web\Widgets\Format\Formatters;

use ZnCore\Base\Legacy\Yii\Helpers\Html;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnLib\Web\Widgets\Format\Entities\AttributeEntity;

class ImageFormatter extends LinkFormatter implements FormatterInterface
{

    public $imageUrlAttribute;
    public $maxSize = 128;

    public function render($value)
    {
        $entity = $this->attributeEntity->getEntity();
        $url = EntityHelper::getValue($entity, $this->imageUrlAttribute);
        $html = Html::img($url, [
            'style' => 'max-width: ' . $this->maxSize . 'px; max-height: ' . $this->maxSize . 'px;',
        ]);
        if($this->uri) {
            return parent::render($html);
        }
        return $html;
    }
}
