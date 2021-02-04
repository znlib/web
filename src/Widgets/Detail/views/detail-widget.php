<?php

/**
 * @var View $this
 * @var Request $request
 * @var PositionEntity $entity
 * @var AttributeEntity[] $attributes
 * @var string $tableClass
 * @var FormatEncoder $formatter
 */

use Packages\Company\Domain\Entities\PositionEntity;
use ZnLib\Web\Widgets\Format\Entities\AttributeEntity;
use ZnLib\Web\Widgets\Format\Libs\FormatEncoder;
use yii\web\Request;
use yii\web\View;
use ZnCore\Base\Libs\I18Next\Facades\I18Next;

?>

<table class="<?= $tableClass ?>">
    <thead>
    <tr>
        <th><?= I18Next::t('core', 'main.title') ?></th>
        <th><?= I18Next::t('core', 'main.value') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($attributes as $attributeEntity):
        //$value = $attributeEntity->getValue();
        $value = $formatter->encode($attributeEntity);
        ?>
        <tr>
            <th><?= $attributeEntity->getLabel() ?></th>
            <td><?= $value ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>