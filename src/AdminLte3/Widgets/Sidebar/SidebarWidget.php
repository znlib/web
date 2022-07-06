<?php

namespace ZnLib\Web\AdminLte3\Widgets\Sidebar;

use ZnCore\Entity\Helpers\CollectionHelper;
use ZnSandbox\Sandbox\Layout\Domain\Interfaces\Services\MenuServiceInterface;
use ZnCore\Entity\Helpers\EntityHelper;
use ZnLib\Web\Widget\Base\BaseWidget2;
use ZnLib\Web\TwBootstrap\Widgets\NavbarMenu\NavbarMenuWidget;

class SidebarWidget extends BaseWidget2
{

    public $menuConfigFile;

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

    private function createWidget(array $items): NavbarMenuWidget
    {
        $nav = new NavbarMenuWidget($items);
        $nav->itemOptions = [
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
            </a>';
        return $nav;
    }
}
