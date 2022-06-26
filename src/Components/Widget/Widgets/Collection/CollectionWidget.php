<?php

namespace ZnLib\Web\Components\Widget\Widgets\Collection;

use Symfony\Component\HttpFoundation\Request;
use ZnLib\Web\Components\Url\Helpers\Url;
use ZnCore\Domain\Entity\Helpers\CollectionHelper;
use ZnCore\Domain\Entity\Helpers\EntityHelper;
use ZnLib\Web\Components\Widget\Base\BaseWidget2;
use ZnLib\Web\Components\Widget\Widgets\Format\Entities\AttributeEntity;
use ZnLib\Web\Components\Widget\Widgets\Format\Libs\FormatEncoder;

class CollectionWidget extends BaseWidget2
{

    public $tableClass = 'table table-striped table-bordered';
    public $collection;
    public $dataProvider;
    public $baseUrl;
    public $filter;
    public $formatterClasses = [];
    public $showStatistic = true;

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
            'showStatistic' => $this->showStatistic,
        ]);
    }

    private function prepareAttributes()
    {
        $this->attributes = CollectionHelper::create(AttributeEntity::class, $this->attributes);
        foreach ($this->attributes as $attributeEntity) {
            $attributeEntity->setEntity($this->collection);
        }
    }
}
