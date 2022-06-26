<?php

/**
 * @var View $this
 * @var AttributeEntity[] $attributes
 * @var string $tableClass
 * @var FormatEncoder $formatter
 */

use ZnLib\Components\I18Next\Facades\I18Next;
use ZnLib\Web\View\Libs\View;
use ZnLib\Web\TwBootstrap\Widgets\Format\Entities\AttributeEntity;
use ZnLib\Web\TwBootstrap\Widgets\Format\Libs\FormatEncoder;

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
