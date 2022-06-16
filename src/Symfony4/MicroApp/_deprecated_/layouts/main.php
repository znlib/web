<!doctype html>
<html lang="en">
<head>
    <?php include 'blocks/head.php' ?>
</head>
<body style="margin-top: 20px; margin-bottom: 20px;">

<div class="container">
    <div class="row">
        <?= \ZnLib\Web\Widgets\Alert\AlertWidget::widget() ?>
        <?= $content ?>
    </div>
</div>

<?php include 'blocks/script.php' ?>

</body>
</html>