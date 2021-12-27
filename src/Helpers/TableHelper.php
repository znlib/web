<?php

namespace ZnLib\Web\Helpers;

use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Domain\Helpers\EntityHelper;

class TableHelper
{

    public static function prepareHeaders(array $headers = []): array
    {
        if ($headers && !ArrayHelper::isIndexed($headers)) {
            $newValue = [];
            foreach ($value as $row) {
                $newRow = [];
                foreach ($headers as $headerName => $headerValue) {
                    $newRow[] = $row[$headerName];
                }
                $newValue[] = $newRow;
            }
            $value = $newValue;
            $headers = array_values($headers);
        }
        return $headers;
    }

    public static function generateHeaderHtml(array $headers): string {
        $headerRowHtml = '';
        foreach ($headers as $cell) {
            $headerRowHtml .= '<th>' . $cell . '</th>';
        }
        if ($headerRowHtml) {
            $headerHtml = '<tr>' . $headerRowHtml . '</tr>';
        } else {
            $headerHtml = '';
        }
        return $headerHtml;
    }

    public static function generateBodyHtml(array $body): string {
        $trList = [];
        foreach ($body as $row) {
            $rowHtml = '';
            foreach ($row as $cell) {
                if (is_object($cell)) {
                    $cell = EntityHelper::toArray($cell);
                }
                if (is_array($cell)) {
                    $cell = '<pre>' . json_encode($cell, JSON_PRETTY_PRINT) . '</pre>';
                }
                $rowHtml .= '<td>' . $cell . '</td>';
            }
            $trList[] = '<tr>' . $rowHtml . '</tr>';
        }
        return implode(PHP_EOL, $trList);
    }

    public static function render(array $body, array $headers = [], string $tableClass = 'table table-bordered table-condensed table-sm')
    {
        $headers = self::prepareHeaders($headers);
        $headerHtml = self::generateHeaderHtml($headers);
        $bodyHtml = self::generateBodyHtml($body);

        $html = '
<small>
<table class="' . $tableClass . '">
    ' . $headerHtml . '
    ' . $bodyHtml . '
</table>
</small>
';
        return $html;
    }
}
