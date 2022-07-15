<?php

namespace ZnLib\Web\AdminLte3\Widgets\NavbarMenu;

use ZnCore\Collection\Helpers\CollectionHelper;
use ZnLib\Web\Menu\Domain\Interfaces\Services\MenuServiceInterface;
use ZnDomain\Entity\Helpers\EntityHelper;
use ZnLib\Web\Widget\Base\BaseWidget2;

class NavbarMenuWidget extends BaseWidget2
{

    public $menuConfigFile;
    public $submenuTemplate = '<ul class="nav nav-treeview">{items}</ul>';

    private $menuService;

    public function __construct(MenuServiceInterface $menuService)
    {
        $this->menuService = $menuService;
    }

    public function run(): string
    {
        $collection = $this->menuService->allByFileName($this->menuConfigFile);
        $items = CollectionHelper::toArray($collection);
        $items = $this->prepareIcon($items);
        $nav = $this->createWidget($items);
        return $nav->render();
    }

    private function prepareIcon(array $items): array
    {
        foreach ($items as &$item) {
            if (!empty($item['icon'])) {
                $item['icon'] .= ' nav-icon';
//                $item['icon'] = 'fas fa-circle nav-icon';
            }
        }
        return $items;
    }

    private function createWidget(array $items): \ZnLib\Web\TwBootstrap\Widgets\NavbarMenu\NavbarMenuWidget
    {
        $nav = new \ZnLib\Web\TwBootstrap\Widgets\NavbarMenu\NavbarMenuWidget($items);
        /*$nav->itemOptions = [
            'class' => 'nav-item',
            'tag' => 'li',
        ];
        $nav->linkTemplate = '
            <a href="{url}" class="nav-link {class}">
                {icon}
                <p>
                    {label}
                    {treeViewIcon}
                    {badge}
                </p>
            </a>';*/
        $nav->submenuTemplate = $this->submenuTemplate;
        return $nav;
    }
}
