<?php

namespace ZnLib\Web\Components\Widget\Widgets\Format\Formatters;

use ZnLib\Web\Components\Widget\Widgets\Format\Entities\AttributeEntity;
use ZnLib\Web\Components\Widget\Widgets\Format\Libs\FormatEncoder;

abstract class BaseFormatter
{

    /** @var AttributeEntity */
    protected $attributeEntity;

    private $formatEncoder;

    public function setAttributeEntity(AttributeEntity $attributeEntity): void
    {
        $this->attributeEntity = $attributeEntity;
    }

    public function setFormatEncoder(FormatEncoder $formatEncoder): void
    {
        $this->formatEncoder = $formatEncoder;
    }

    public function getFormatEncoder(): FormatEncoder
    {
        return $this->formatEncoder;
    }

}
