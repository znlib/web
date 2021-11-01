<?php

/**
 * @var string $inputName
 * @var string $label
 * @var $value
 */

use yii\helpers\Html;

?>

<?= Html::input('text', $inputName, $value, [
    'class' => 'form-control',
    'placeholder' => $label,
]); ?>
