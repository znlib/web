<?php

namespace ZnLib\Web\Widgets\Collection;

use Symfony\Component\HttpFoundation\Request;
use ZnCore\Base\Legacy\Yii\Helpers\Url;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnLib\Web\Widgets\Base\BaseWidget2;
use ZnLib\Web\Widgets\Format\Entities\AttributeEntity;
use ZnLib\Web\Widgets\Format\Libs\FormatEncoder;

class CollectionWidget extends BaseWidget2
{

    public $tableClass = 'table table-striped table-bordered';
    public $collection;
    public $dataProvider;
    public $baseUrl;
    public $filter;
    public $formatterClasses = [];

    /** @var AttributeEntity[] | array */
    public $attributes;

    public function run(): string
    {
        $request = Request::createFromGlobals();
        $formatterEncoder = new FormatEncoder();
        $formatterEncoder->setFormatterClasses($this->formatterClasses);
        $this->prepareAttributes();
        return $this->render('index', [
            'tableClass' => $this->tableClass,
            'collection' => $this->collection,
            'dataProvider' => $this->dataProvider,
            'attributes' => $this->attributes,
            'baseUrl' => Url::getBaseUrl(),
            'formatter' => $formatterEncoder,
            'queryParams' => $request->query->all(),
            'filterModel' => $this->filter,
        ]);
    }

    private function prepareAttributes()
    {
        $this->attributes = EntityHelper::createEntityCollection(AttributeEntity::class, $this->attributes);
        foreach ($this->attributes as $attributeEntity) {
            $attributeEntity->setEntity($this->collection);
        }
    }
}
