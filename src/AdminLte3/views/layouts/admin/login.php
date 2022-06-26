<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use Packages\Application\Facades\BrandFacade;
use yii\helpers\Url;
use ZnYii\Web\Widgets\Toastr\Toastr;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?= $this->render('block/head') ?>
</head>
<body class="hold-transition login-page">
<?php $this->beginBody() ?>

<div class="login-box">
    <div class="login-logo">
        <a href="<?= Url::base() ?>">
            <img src="<?= BrandFacade::getCompanyEntity()->getLogoUrl() ?>" alt="Logo" class="img-circle" style="width: 64px">
            <?= BrandFacade::getCompanyEntity()->getTitle() ?>
        </a>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <?= \ZnLib\Web\TwBootstrap\Widgets\Alert\AlertWidget::widget() ?>
            <?= $content ?>
        </div>
    </div>
</div>
<?= Toastr::widget() ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
