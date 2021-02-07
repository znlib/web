<?php

/**
 * @var View $this
 * @var Collection $collection
 * @var DataProvider $dataProvider
 * @var AttributeEntity[] $attributes
 * @var string $tableClass
 * @var string $baseUrl
 * @var array $queryParams
 * @var FormatEncoder $formatter
 * @var object $filterModel
 */

use Illuminate\Support\Collection;
use ZnCore\Base\Libs\I18Next\Facades\I18Next;
use ZnCore\Domain\Libs\DataProvider;
use ZnLib\Web\View\View;
use ZnLib\Web\Widgets\Format\Entities\AttributeEntity;
use ZnLib\Web\Widgets\Format\Libs\FormatEncoder;
use ZnLib\Web\Widgets\Pagination\PaginationWidget;

if (!isset($collection)) {
    $collection = $dataProvider->getCollection();
}

?>

<table class="<?= $tableClass ?>">
    <thead>
    <?= $this->render('head', [
        'attributes' => $attributes,
        'baseUrl' => $baseUrl,
        'queryParams' => $queryParams,
    ]) ?>
    <?php if ($filterModel): ?>
        <?= $this->render('filter', [
            'attributes' => $attributes,
            'filterModel' => $filterModel,
        ]) ?>
    <?php endif; ?>
    </thead>
    <tbody>
    <?= $this->render('body', [
        'attributes' => $attributes,
        'formatter' => $formatter,
        'collection' => $collection,
    ]) ?>
    </tbody>
    <?php if ($collection->count()): ?>
        <tfoot>
        <tr>
            <td colspan="<?= count($attributes) ?>">
                <?= I18Next::t('web', 'collection.items_collection_count') ?>:
                <?= $collection->count() ?>

                <?php if (!empty($dataProvider)): ?>
                    <span class="text-muted">
            /
        </span>
                    <?= I18Next::t('web', 'collection.items_total_count') ?>:
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
