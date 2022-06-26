<?php

namespace ZnLib\Web\Components\TwBootstrap\Widgets\Format\Formatters;

use ZnCore\Base\Arr\Helpers\ArrayHelper;
use ZnLib\Web\Components\Html\Helpers\Html;
use ZnCore\Domain\Entity\Helpers\EntityHelper;

class LinkFormatter extends BaseFormatter implements FormatterInterface
{

    public $enumClass;
    public $linkAttribute = 'id';
    public $linkParam = 'id';
    public $uri;

    public function render($value)
    {
        $entity = $this->attributeEntity->getEntity();
        if($this->attributeEntity->getAttributeName()) {
            $title = EntityHelper::getValue($entity, $this->attributeEntity->getAttributeName());
        } else {
            $title = $value;
        }
        $link = EntityHelper::getValue($entity, $this->linkAttribute);
        $uri = ArrayHelper::toArray($this->uri);
        $uri[$this->linkParam] = $link;
        return Html::a($title, $uri);
    }
}
