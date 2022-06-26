<?php

namespace ZnLib\Web\Components\Widget\Widgets\Format\Libs;

use DateTime;
use ZnCore\Base\Instance\Helpers\ClassHelper;
use ZnCore\Base\Arr\Helpers\ArrayHelper;
use ZnLib\Web\Components\Widget\Widgets\Format\Entities\AttributeEntity;
use ZnLib\Web\Components\Widget\Widgets\Format\Enums\TypeEnum;
use ZnLib\Web\Components\Widget\Widgets\Format\Formatters\ArrayFormatter;
use ZnLib\Web\Components\Widget\Widgets\Format\Formatters\BooleanFormatter;
use ZnLib\Web\Components\Widget\Widgets\Format\Formatters\DoubleFormatter;
use ZnLib\Web\Components\Widget\Widgets\Format\Formatters\EnumFormatter;
use ZnLib\Web\Components\Widget\Widgets\Format\Formatters\FormatterInterface;
use ZnLib\Web\Components\Widget\Widgets\Format\Formatters\HtmlFormatter;
use ZnLib\Web\Components\Widget\Widgets\Format\Formatters\IntegerFormatter;
use ZnLib\Web\Components\Widget\Widgets\Format\Formatters\NullFormatter;
use ZnLib\Web\Components\Widget\Widgets\Format\Formatters\ObjectFormatter;
use ZnLib\Web\Components\Widget\Widgets\Format\Formatters\ResourceClosedFormatter;
use ZnLib\Web\Components\Widget\Widgets\Format\Formatters\ResourceFormatter;
use ZnLib\Web\Components\Widget\Widgets\Format\Formatters\StringFormatter;
use ZnLib\Web\Components\Widget\Widgets\Format\Formatters\TimeFormatter;
use ZnLib\Web\Components\Widget\Widgets\Format\Formatters\UnknownTypeFormatter;

class FormatEncoder
{

    private $formatterClasses = [];

    public function getFormatterClasses(): array
    {
        return $this->formatterClasses;
    }

    public function setFormatterClasses(array $formatterClasses): void
    {
        $this->formatterClasses = ArrayHelper::merge($this->getDefaultFormatterClasses(), $formatterClasses);
    }

    public function encode(AttributeEntity $attributeEntity): string
    {
        $value = $attributeEntity->getValue();
        /*if($value == null) {
            return '--';
        }*/
        $formatterInstance = $this->getFormatterInstance($attributeEntity);
        $formatterInstance->setAttributeEntity($attributeEntity);
        $formatterInstance->setFormatEncoder($this);
        return $formatterInstance->render($value);
    }

    public function encodeValue($value): string
    {

    }

    private function getFormatterInstance(AttributeEntity $attributeEntity): FormatterInterface
    {
        $formatterClass = $this->getFormatterClass($attributeEntity);
        return ClassHelper::createObject($formatterClass);
    }

    private function getFormatterClass(AttributeEntity $attributeEntity)//: string
    {
        $formatterClasses = $this->getFormatterClasses();
        $value = $attributeEntity->getValue();
        $valueType = gettype($value);
        if ($valueType == TypeEnum::NULL) {
            return $formatterClasses[TypeEnum::NULL];
        }
        if ($attributeEntity->getFormatter()) {
            return $attributeEntity->getFormatter();
        }
        $format = $attributeEntity->getFormat();
        if ($format) {
            return ArrayHelper::getValue($formatterClasses, $format, TypeEnum::STRING);
        }
        if ($valueType == TypeEnum::OBJECT) {
            $valueClass = get_class($value);
            if (isset($formatterClasses[$valueClass])) {
                return $formatterClasses[$valueClass];
            }
        }
        if (isset($formatterClasses[$valueType])) {
            return $formatterClasses[$valueType];
        }
        return ArrayHelper::getValue($formatterClasses, TypeEnum::STRING);
    }

    private function getDefaultFormatterClasses(): array
    {
        return [
            'html' => HtmlFormatter::class,
            'enum' => EnumFormatter::class,

            TypeEnum::BOOLEAN => BooleanFormatter::class,
            TypeEnum::INTEGER => IntegerFormatter::class,
            TypeEnum::DOUBLE => DoubleFormatter::class,
            TypeEnum::STRING => StringFormatter::class,
            TypeEnum::ARRAY => ArrayFormatter::class,
            TypeEnum::OBJECT => ObjectFormatter::class,
            TypeEnum::RESOURCE => ResourceFormatter::class,
            TypeEnum::RESOURCE_CLOSED => ResourceClosedFormatter::class,
            TypeEnum::NULL => NullFormatter::class,
            TypeEnum::UNKNOWN_TYPE => UnknownTypeFormatter::class,

            DateTime::class => TimeFormatter::class,
        ];
    }
}
