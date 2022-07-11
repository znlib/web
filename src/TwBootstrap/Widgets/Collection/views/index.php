<?php

/**
 * @var View $this
 * @var Enumerable $collection
 * @var DataProvider $dataProvider
 * @var AttributeEntity[] $attributes
 * @var string $tableClass
 * @var string $baseUrl
 * @var array $queryParams
 * @var FormatEncoder $formatter
 * @var object $filterModel
 * @var bool $showStatistic
 */

use ZnCore\Collection\Interfaces\Enumerable;
use ZnCore\DataProvider\Libs\DataProvider;
use ZnLib\I18Next\Facades\I18Next;
use ZnLib\Web\TwBootstrap\Widgets\Format\Entities\AttributeEntity;
use ZnLib\Web\TwBootstrap\Widgets\Format\Libs\FormatEncoder;
use ZnLib\Web\TwBootstrap\Widgets\Pagination\PaginationWidget;
use ZnLib\Web\View\Libs\View;

if (!isset($collection)) {
    $collection = $dataProvider->getCollection();
}

?>

<table class="<?= $tableClass ?>">
    <thead>
    <?= $this->renderFile(__DIR__ . '/head.php', [
        'attributes' => $attributes,
        'baseUrl' => $baseUrl,
        'queryParams' => $queryParams,
    ]) ?>
    <?php if ($filterModel): ?>
        <?= $this->renderFile(__DIR__ . '/filter.php', [
            'attributes' => $attributes,
            'filterModel' => $filterModel,
        ]) ?>
    <?php endif; ?>
    </thead>
    <tbody>
    <?= $this->renderFile(__DIR__ . '/body.php', [
        'attributes' => $attributes,
        'formatter' => $formatter,
        'collection' => $collection,
    ]) ?>
    </tbody>
    <?php if ($collection->count() && $showStatistic): ?>
        <tfoot>
        <tr>
            <td colspan="<?= count($attributes) ?>">
                <?= I18Next::t('web', 'collection.items_collection_count') ?>:
                <?= $collection->count() ?>

                <?php if (!empty($dataProvider)): ?>
                    <?= I18Next::t('web', 'collection.items_of') ?>
                    <?= $dataProvider->getTotalCount() ?>
                <?php endif; ?>
            </td>
        </tr>
        </tfoot>
    <?php endif; ?>
</table>

<?php
if (isset($dataProvider)) {
    echo PaginationWidget::widget(['dataProvider' => $dataProvider]);
}
?>

<?php //if (!$collection->isEmpty()): ?>
<!---->
<?php //else: ?>
<!--    <div class="alert alert-secondary" role="alert">-->
<!--        --><? //= I18Next::t('web', 'message.empty_list') ?>
<!--    </div>-->
<?php //endif; ?>
