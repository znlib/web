<?php

namespace ZnLib\Web\Menu\Domain\Repositories\File;

use ZnLib\Web\Menu\Domain\Entities\MenuEntity;
use ZnLib\Web\Menu\Domain\Interfaces\Repositories\MenuRepositoryInterface;
use ZnDomain\Сomponents\FileRepository\Base\BaseFileCrudRepository;

class MenuRepository extends BaseFileCrudRepository implements MenuRepositoryInterface
{

    private $fileName;

    public function getEntityClass(): string
    {
        return MenuEntity::class;
    }

    public function setFileName( string $fileName): void
    {
        $this->fileName = $fileName;
    }

    public function fileName(): string
    {
        return $this->fileName;
    }
}
