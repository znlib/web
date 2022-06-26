<?php

/**
 * @var View $this
 * @var string $baseUrl
 * @var array $queryParams
 * @var AttributeEntity[] $attributes
 */

use ZnLib\Web\TwBootstrap\Widgets\Format\Entities\AttributeEntity;
use ZnLib\Web\Html\Helpers\HtmlHelper;
use ZnLib\Web\View\Libs\View;

?>

<tr>
    <?php foreach ($attributes as $attributeEntity): ?>
        <th>
            <?php
            if ($attributeEntity->getSortAttribute()) {
                echo \ZnLib\Web\TwBootstrap\Widgets\Collection\Helpers\CollectionWidgetHelper::sortByField($attributeEntity->getLabel(), $attributeEntity->getSortAttribute(), $baseUrl, $queryParams);
            } else {
                echo $attributeEntity->getLabel();
            }
            ?>
        </th>
    <?php endforeach; ?>
</tr>
