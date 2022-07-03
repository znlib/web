<?php

/**
 * @var View $this
 * @var Collection $collection
 * @var AttributeEntity[] $attributes
 * @var FormatEncoder $formatter
 */

use ZnCore\Domain\Collection\Libs\Collection;
use ZnLib\Web\TwBootstrap\Widgets\Format\Entities\AttributeEntity;
use ZnLib\Web\TwBootstrap\Widgets\Format\Libs\FormatEncoder;
use ZnLib\Web\View\Libs\View;
use ZnLib\Components\I18Next\Facades\I18Next;

?>

<?php if (!$collection->isEmpty()): ?>
    <?php foreach ($collection as $entity): ?>
        <tr>
            <?php foreach ($attributes as $attributeEntity):
                $attributeEntity->setEntity($entity);
                $value = $formatter->encode($attributeEntity);
                ?>
                <td><?= $value ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="<?= count($attributes) ?>">
            <i class="text-muted">
                <?= I18Next::t('web', 'message.empty_list') ?>
            </i>
        </td>
    </tr>
<?php endif; ?>
