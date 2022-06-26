<?php

/**
 * @var View $this
 * @var object $model
 * @var array $attributes
 */

use yii\helpers\Html;
use ZnLib\Components\I18Next\Facades\I18Next;
use ZnCore\Domain\Entity\Helpers\EntityHelper;
use ZnLib\Web\Components\View\Libs\View;

?>

<form id="filter" class="form-row mt-4 collapse filter" method="get" action="<?= '/' . Yii::$app->request->pathInfo ?>">
    <?php foreach ($attributes as $attribute):
        $name = "filter[{$attribute['name']}]";
        $attribute['inputName'] = "filter[{$attribute['name']}]";
        $label = $attribute['label'];
        $type = $attribute['type'] ?? 'text';
        $attribute['value'] = EntityHelper::getValue($model, $attribute['name']);
        ?>
        <div class="form-group col-lg-2 col-md-6">
            <?= Html::label('Title', $name, ['class' => 'sr-only']); ?>
            <?= $this->renderFile(__DIR__ . "/types/{$type}.php", $attribute) ?>
        </div>
    <?php endforeach; ?>
    <button type="submit" class="d-none"></button>
</form>

<div class="row align-items-center mt-4 mb-3">
    <div class="col-md-auto mb-md-0 mb-2">
        <span class="text-sm mr-2"><?= I18Next::t('layout', 'collection.filter.title') ?>:</span>
        <a class="btn btn-sm btn-link dropdown-toggle p-0" data-toggle="collapse" href="#filter" role="button"
           aria-expanded="false" aria-controls="filter">
            <?= I18Next::t('layout', 'collection.filter.collapse') ?>
        </a>
    </div>
</div>
