<?php

namespace ZnLib\Web\TwBootstrap\Widgets\Table\Helpers;

use ZnCore\Base\Arr\Helpers\ArrayHelper;
use ZnCore\Domain\Entity\Helpers\EntityHelper;

class TableWidgetHelper
{

    public static function prepareHeaders(array $headers = []): array
    {
        if ($headers && !ArrayHelper::isIndexed($headers)) {
            $newValue = [];
            /*foreach ($value as $row) {
                $newRow = [];
                foreach ($headers as $headerName => $headerValue) {
                    $newRow[] = $row[$headerName];
                }
                $newValue[] = $newRow;
            }
            $value = $newValue;*/
            $headers = array_values($headers);
        }
        return $headers;
    }

    public static function prepareBody(array $body): array
    {
        foreach ($body as &$row) {
            $rowHtml = '';
            foreach ($row as &$cell) {
                if (is_object($cell)) {
                    $cell = EntityHelper::toArray($cell);
                }
                if (is_array($cell)) {
                    $cell = '<pre>' . json_encode($cell, JSON_PRETTY_PRINT) . '</pre>';
                }
            }
        }
        return $body;
    }
}
