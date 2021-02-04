<?php

namespace ZnLib\Web\Widgets\Format\Formatters;

use ZnLib\Web\Widgets\Format\Entities\AttributeEntity;

abstract class BaseFormatter
{

    /** @var AttributeEntity */
    protected $attributeEntity;

    public function setAttributeEntity(AttributeEntity $attributeEntity): void
    {
        $this->attributeEntity = $attributeEntity;
    }
}
