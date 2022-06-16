<?php

/**
 * @var View $this
 */

use ZnLib\Web\View\View;

?>

<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<!-- Fontawesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />

<title><?= $title ?? '' ?></title>

<?php
if($this instanceof View) {
    foreach ($this->getCssFiles() as $item) {
        $options = $item['options'];
        $options['rel'] = 'stylesheet';
        $options['href'] = $item['file'];
        echo \ZnCore\Base\Legacy\Yii\Helpers\Html::tag('link', '', $options);
    }
}
?>

<?php if($this instanceof View): ?>
    <style>
            <?= $this->getCssCode() ?>
    </style>
<?php endif; ?>
