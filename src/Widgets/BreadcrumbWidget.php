<?php

namespace ZnLib\Web\Widgets;

class BreadcrumbWidget extends MenuWidget
{

    public $itemOptions = [
        'class' => 'breadcrumb-item',
    ];
    public $linkTemplate = '<a href="{url}" class="{class}">{icon}{label}{treeViewIcon}{badge}</a>';
    public $wrapTemplate = '<ol class="breadcrumb">{items}</ol>';
    public $encodeLabels = false;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

}