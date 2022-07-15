<?php

namespace ZnLib\Web\TwBootstrap\Widgets\Detail;

use DateTime;
use ZnDomain\Entity\Helpers\CollectionHelper;
use ZnLib\Web\TwBootstrap\Widgets\Format\Entities\AttributeEntity;
use ZnLib\Web\TwBootstrap\Widgets\Format\Enums\TypeEnum;
use ZnLib\Web\TwBootstrap\Widgets\Format\Formatters\ArrayFormatter;
use ZnLib\Web\TwBootstrap\Widgets\Format\Formatters\BooleanFormatter;
use ZnLib\Web\TwBootstrap\Widgets\Format\Formatters\DoubleFormatter;
use ZnLib\Web\TwBootstrap\Widgets\Format\Formatters\EnumFormatter;
use ZnLib\Web\TwBootstrap\Widgets\Format\Formatters\HtmlFormatter;
use ZnLib\Web\TwBootstrap\Widgets\Format\Formatters\IntegerFormatter;
use ZnLib\Web\TwBootstrap\Widgets\Format\Formatters\NullFormatter;
use ZnLib\Web\TwBootstrap\Widgets\Format\Formatters\ObjectFormatter;
use ZnLib\Web\TwBootstrap\Widgets\Format\Formatters\ResourceClosedFormatter;
use ZnLib\Web\TwBootstrap\Widgets\Format\Formatters\ResourceFormatter;
use ZnLib\Web\TwBootstrap\Widgets\Format\Formatters\StringFormatter;
use ZnLib\Web\TwBootstrap\Widgets\Format\Formatters\TimeFormatter;
use ZnLib\Web\TwBootstrap\Widgets\Format\Formatters\UnknownTypeFormatter;
use ZnLib\Web\TwBootstrap\Widgets\Format\Libs\FormatEncoder;
use ZnCore\Arr\Helpers\ArrayHelper;
use ZnDomain\Entity\Helpers\EntityHelper;
use ZnLib\Web\Widget\Base\BaseWidget2;

class DetailWidget extends BaseWidget2
{

    public $tableClass = 'table table-striped table-bordered';
    public $entity;
    public $formatterClasses = [];

    /** @var AttributeEntity[] | array */
    public $attributes;

    public function run(): string
    {
        $formatterEncoder = new FormatEncoder();
        $formatterEncoder->setFormatterClasses($this->getFormatterClasses());
        $this->prepareAttributes();
        return $this->render('detail-widget', [
            'tableClass' => $this->tableClass,
            'entity' => $this->entity,
            'attributes' => $this->attributes,
            'formatter' => $formatterEncoder,
        ]);
    }

    private function prepareAttributes()
    {
        $this->attributes = CollectionHelper::create(AttributeEntity::class, $this->attributes);
        foreach ($this->attributes as $attributeEntity) {
            $attributeEntity->setEntity($this->entity);
        }
    }

    private function getFormatterClasses(): array
    {
        $formatterClasses = [
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
        return ArrayHelper::merge($formatterClasses, $this->formatterClasses);
    }
}
