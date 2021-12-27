<?php

namespace ZnLib\Web\Widgets\Table;

use ZnLib\Web\Widgets\Base\BaseWidget2;
use ZnLib\Web\Widgets\Table\Helpers\TableWidgetHelper;

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
        $body = TableWidgetHelper::prepareBody($this->body ?: []);
        return $this->render('table-widget', [
            'tableClass' => $this->tableClass,
            'headerRow' => $headers,
            'bodyRows' => $body,
        ]);
    }
}
