<?php

namespace ZnLib\Web\TwBootstrap\Widgets\Collection;

use Symfony\Component\HttpFoundation\Request;
use ZnCore\Entity\Helpers\CollectionHelper;
use ZnLib\Components\Http\Helpers\UrlHelper;
use ZnLib\Web\TwBootstrap\Widgets\Format\Entities\AttributeEntity;
use ZnLib\Web\TwBootstrap\Widgets\Format\Libs\FormatEncoder;
use ZnLib\Web\Widget\Base\BaseWidget2;

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
            'baseUrl' => UrlHelper::requestUri(),
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
