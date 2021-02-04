<?php

namespace ZnLib\Web\Widgets\Format\Formatters;

use ZnLib\Web\Widgets\Format\Entities\AttributeEntity;

interface FormatterInterface
{

    public function render($items);

    public function setAttributeEntity(AttributeEntity $attributeEntity): void;
}
