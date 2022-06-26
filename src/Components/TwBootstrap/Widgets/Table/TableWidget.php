<?php

namespace ZnLib\Web\Components\TwBootstrap\Widgets\Table;

use ZnCore\Base\Arr\Helpers\ArrayHelper;
use ZnLib\Web\Components\Widget\Base\BaseWidget2;
use ZnLib\Web\Components\TwBootstrap\Widgets\Table\Helpers\TableWidgetHelper;

class TableWidget extends BaseWidget2
{

    public $tableClass = 'table table-striped table-bordered';
    public $header = [];
    public $body = [];

    public function run(): string
    {
        if (empty($this->body) && empty($this->header)) {
            return '';
        }
        $headers = TableWidgetHelper::prepareHeaders($this->header ?: []);

        if($this->header) {
            $keys = ArrayHelper::isIndexed($this->header) ? array_values($this->header) : array_keys($this->header);
            $this->body = ArrayHelper::collectionExtractByKeys($this->body, $keys);
        }

        $body = TableWidgetHelper::prepareBody($this->body ?: []);
        return $this->render('table-widget', [
            'tableClass' => $this->tableClass,
            'headerRow' => $headers,
            'bodyRows' => $body,
        ]);
    }
}