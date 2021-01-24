<?php

namespace ZnLib\Web\Widgets;

use Symfony\Component\HttpFoundation\Request;
use ZnCore\Domain\Entities\DataProviderEntity;
use ZnCore\Domain\Libs\DataProvider;
use ZnLib\Web\Widgets\Base\BaseWidget2;

class PaginationWidget2 extends BaseWidget2
{

    /** @var DataProvider */
    public $dataProvider;
    private $dataProviderEntity;
    private $request;
    private $perPageOptions = [10, 20, 50];

    public function __construct(DataProvider $dataProvider = null, Request $request = null)
    {
        $request = $request ?: Request::createFromGlobals();
        $this->request = $request;
    }

    public function init()
    {
        $this->dataProviderEntity = $this->dataProvider->getEntity();
        $this->dataProviderEntity->setTotalCount($this->dataProvider->getTotalCount());
    }

    public function run(): string
    {
        $this->init();
        if ($this->dataProviderEntity->getPageCount() == 1) {
            return '';
        }
        $itemsHtml = $this->renderItems();
        $renderPageSizeSelector = $this->renderPageSizeSelector();
        $itemsHtml .= $renderPageSizeSelector ? '<li class="page-item">' . $renderPageSizeSelector . '</li>' : '';
        return $this->renderLayout($itemsHtml);
    }

    private function generateUrl(int $page = 1)
    {
        $queryParams = $this->request->query->all();
        $queryParams['per-page'] = $this->dataProviderEntity->getPageSize();
        $queryParams['page'] = $page;
        $queryString = http_build_query($queryParams);
        return '?' . $queryString;
    }

    private function generateItemsData(int $pageStart, int $pageEnd)
    {
        $items = [];
        for ($page = $pageStart; $page <= $pageEnd; $page++) {
            $isActive = $this->dataProviderEntity->getPage() == $page;
            $items[] = [
                'label' => $page,
                'url' => $this->generateUrl($page),
                'active' => $isActive ? 'active' : '',
            ];
        }
        return $items;
    }

    private function renderItems()
    {
        $items = [];

        $items[] = [
            'label' => '&laquo;',
            'url' => $this->generateUrl($this->dataProviderEntity->getPrevPage()),
            'encode' => false,
            'options' => ['class' => ($this->dataProviderEntity->isFirstPage() ? 'page-item disabled' : 'page-item')],
        ];

        $its = $this->generateItemsData(1, $this->dataProviderEntity->getPageCount());
        $items = array_merge($items, $its);

        $items[] = [
            'label' => '&raquo;',
            'url' => $this->generateUrl($this->dataProviderEntity->getNextPage()),
            'encode' => false,
            'options' => ['class' => ($this->dataProviderEntity->isLastPage() ? 'page-item disabled' : 'page-item')],
        ];

        $menuWidget = new MenuWidget;
        $menuWidget->items = $items;
        $menuWidget->itemOptions = [
            'class' => 'page-item',
        ];
        $menuWidget->linkTemplate = '<a href="{url}" class="page-link {class}">{label}</a>';
        $itemsHtml = $menuWidget->render();
        return $itemsHtml;
    }

    private function renderLayout(string $items)
    {
        return "
            <nav aria-label=\"Page navigation\">
                <ul class=\"pagination justify-content-end\">
                    {$items}
                </ul>
            </nav>
        ";
    }

    private function renderPageSizeSelector()
    {
        if (empty($this->perPageOptions)) {
            return '';
        }
        $html = '';
        $queryParams = $this->request->query->all();
        foreach ($this->perPageOptions as $size) {
            $queryParams['per-page'] = $size;
            $queryParams['page'] = 1;
            $queryString = http_build_query($queryParams);
            $html .= "<a class=\"dropdown-item\" href='?{$queryString}'>{$size}</a>";
        }
        return "
            <li class=\"page-item \">
                <a class=\"page-link dropdown-toggle\" href=\"#\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                    {$this->dataProviderEntity->getPageSize()}
                </a>
                <div class=\"dropdown-menu dropdown-menu-right\" aria-labelledby=\"navbarDropdown\">
                    <h6 class=\"dropdown-header\">Page size</h6>
                    {$html}
                </div>
            </li>";
    }

}