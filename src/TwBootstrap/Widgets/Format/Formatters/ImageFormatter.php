<?php

namespace ZnLib\Web\TwBootstrap\Widgets\Format\Formatters;

use ZnCore\Code\Helpers\PropertyHelper;
use ZnLib\Web\Html\Helpers\Html;

class ImageFormatter extends LinkFormatter implements FormatterInterface
{

    public $imageUrlAttribute;
    public $maxSize = 128;

    public function render($value)
    {
        $entity = $this->attributeEntity->getEntity();
        $url = PropertyHelper::getValue($entity, $this->imageUrlAttribute);
        $html = Html::img($url, [
            'style' => 'max-width: ' . $this->maxSize . 'px; max-height: ' . $this->maxSize . 'px;',
        ]);
        if ($this->uri) {
            return parent::render($html);
        }
        return $html;
    }
}
