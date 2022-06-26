<?php

namespace ZnLib\Web\Components\Widget\Widgets\Format\Formatters;

use ZnLib\Web\Components\Widget\Widgets\Format\Entities\AttributeEntity;

interface FormatterInterface
{

    public function render($items);

    public function setAttributeEntity(AttributeEntity $attributeEntity): void;
}
