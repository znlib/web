<?php

namespace ZnLib\Web\Symfony4\MicroApp;

use Illuminate\Container\Container;
use Psr\Container\ContainerInterface;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Domain\Interfaces\Libs\EntityManagerInterface;

class EntityManagerHelper
{

    public static function bindEntityManager(ContainerInterface $container, $entitiesConfig): void
    {
        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);
        if (!empty($entitiesConfig)) {
            foreach ($entitiesConfig as $entityClass => $repositoryInterface) {
                $em->bindEntity($entityClass, $repositoryInterface);
            }
        }
    }
}
