<?php

/**
 * @var array $menuConfigFile
 * @var View $this
 * @var string $content
 */

use ZnLib\Web\AdminApp\Assets\AdminAppAsset;
use ZnLib\Web\Layout\Widgets\Script\ScriptWidget;
use ZnLib\Web\Layout\Widgets\Style\StyleWidget;
use ZnLib\Web\View\Libs\View;
use ZnLib\Web\Widget\Widgets\Toastr\ToastrWidget;

(new AdminAppAsset())->register($this);

//$this->registerCssFile('/static/css/footer.css');
//$this->registerCssFile('/static/css/site.css');

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $title ?? '' ?></title>
    <?= StyleWidget::widget(['view' => $this]) ?>
</head>
<body>

<?= \ZnLib\Web\TwBootstrap\Widgets\Alert\AlertWidget::widget() ?>
<?= $content ?>

<?= ToastrWidget::widget(['view' => $this]) ?>
<?= StyleWidget::widget(['view' => $this]) ?>
<?= ScriptWidget::widget(['view' => $this]) ?>

</body>
</html>
