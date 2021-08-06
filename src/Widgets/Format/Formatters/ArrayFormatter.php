<?php

namespace ZnLib\Web\Widgets\Format\Formatters;

use ZnCore\Base\Helpers\StringHelper;
use ZnCore\Base\Helpers\TemplateHelper;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;

class ArrayFormatter extends BaseFormatter implements FormatterInterface
{

    public $indexedItemWrapper = '<span class="badge badge-primary">{value}</span>';
    public $indexedSplitter = ' ';
    public $assocWrapper = '<ul>{items}</ul>';
    public $assocItemWrapper = '<li>{key}: {value}</li>';

    public function render($items)
    {
        if (ArrayHelper::isIndexed($items)) {
            return $this->formatIndexed($items);
        } else {
            return $this->formatAssoc($items);
        }
    }

    private function formatIndexed(array $items): string
    {
        $arr = [];
        foreach ($items as $key => $val) {
            $arr[] = TemplateHelper::renderTemplate($this->indexedItemWrapper, ['value' => $val]);
        }
        return implode($this->indexedSplitter, $arr);
    }

    private function formatAssoc(array $items): string
    {
        $arr = [];
        foreach ($items as $key => $val) {
            $arr[] = TemplateHelper::renderTemplate($this->assocItemWrapper, [
                'key' => $key,
                'value' => $val,
            ]);
        }
        $itemsHtml = implode(' ', $arr);
        return TemplateHelper::renderTemplate($this->assocWrapper, ['items' => $itemsHtml]);
    }
}
