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
        $headers = TableWidgetHelper::prepareHeaders($this->header);
//        $headerHtml = TableWidgetHelper::generateHeaderHtml($headers);
        $body = TableWidgetHelper::prepareBody($this->body);
//        $bodyHtml = TableWidgetHelper::generateBodyHtml($body);

        return $this->render('table-widget', [
            'tableClass' => $this->tableClass,
//            'headerHtml' => $headerHtml,
//            'bodyHtml' => $bodyHtml,
            'headerRow' => $headers,
            'bodyRows' => $body,
        ]);
    }
}
