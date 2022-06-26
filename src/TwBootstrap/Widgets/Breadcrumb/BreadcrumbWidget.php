<?php

namespace ZnLib\Web\TwBootstrap\Widgets\Breadcrumb;

use ZnLib\Web\TwBootstrap\Widgets\Menu\MenuWidget;

class BreadcrumbWidget extends MenuWidget
{

    public $itemOptions = [
        'class' => 'breadcrumb-item',
    ];
    public $linkTemplate = '<a href="{url}" class="{class}">{icon}{label}{treeViewIcon}{badge}</a>';
    public $wrapTemplate = '<ol class="breadcrumb">{items}</ol>';
    public $encodeLabels = false;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function add(string $label, string $url)
    {
        $this->items[] = [
            'label' => $label,
            'url' => $url,
        ];
    }

    public function addItem(array $item)
    {
        $this->items[] = $item;
    }
}