<?php

namespace ZnLib\Web\Components\TwBootstrap\Widgets\Format\Formatters;

use ZnLib\Web\Components\TwBootstrap\Widgets\Format\Entities\AttributeEntity;

interface FormatterInterface
{

    public function render($items);

    public function setAttributeEntity(AttributeEntity $attributeEntity): void;
}
