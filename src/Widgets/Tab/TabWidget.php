<?php

namespace ZnLib\Web\Widgets\Tab;

use ZnLib\Web\Widgets\Base\BaseWidget2;

class TabWidget extends BaseWidget2
{

    public $contentClass;
    public $class;
    public $items = [];

    public function run(): string
    {
        $hasActive = false;
        foreach ($this->items as &$item) {
            $item['is_active'] = $item['is_active'] ?? false;
            if($item['is_active']) {
                $hasActive = true;
            }
        }
        if (!$hasActive) {
            $this->items[0]['is_active'] = true;
        }
        return $this->render('index', [
            'contentClass' => $this->contentClass,
            'class' => $this->class,
            'items' => $this->items,
        ]);
    }
}
