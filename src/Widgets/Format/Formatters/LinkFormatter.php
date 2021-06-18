<?php

namespace ZnLib\Web\Widgets\Format\Formatters;

use yii\helpers\ArrayHelper;
use ZnCore\Base\Legacy\Yii\Helpers\Html;
use ZnCore\Domain\Helpers\EntityHelper;

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
