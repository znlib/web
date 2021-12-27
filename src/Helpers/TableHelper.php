<?php

namespace ZnLib\Web\Helpers;

use ZnLib\Web\Widgets\Table\TableWidget;

class TableHelper
{

    public static function render(array $body, array $headers = [], string $tableClass = 'table table-bordered table-condensed table-sm')
    {
        return TableWidget::widget([
            'tableClass' => $tableClass,
            'body' => $body,
            'header' => $headers,
        ]);
    }
}
