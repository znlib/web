<?php

/**
 * @var View $this
 */

use ZnLib\Web\View\View;

if($this instanceof View) {
    foreach ($this->getCssFiles() as $item) {
        $options = $item['options'];
        $options['rel'] = 'stylesheet';
        $options['href'] = $item['file'];
        if(\ZnCore\Base\Libs\DotEnv\DotEnv::get('ASSET_FORCE_RELOAD', false)) {
            $options['href'] .= '?timestamp=' . time();
        }
        echo \ZnCore\Base\Legacy\Yii\Helpers\Html::tag('link', '', $options);
    }
    $this->resetCssFiles();
    ?>
    <style>
        <?= $this->getCssCode() ?>
    </style>
    <?php
    $this->resetCssCode();
}
?>
