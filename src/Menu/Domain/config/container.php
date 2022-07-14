<?php

return [
    'singletons' => [
        'ZnLib\Web\Menu\Domain\Interfaces\Services\MenuServiceInterface' => 'ZnLib\Web\Menu\Domain\Services\MenuService',
        'ZnLib\Web\Menu\Domain\Interfaces\Repositories\MenuRepositoryInterface' => 'ZnLib\Web\Menu\Domain\Repositories\File\MenuRepository',
    ],
];
