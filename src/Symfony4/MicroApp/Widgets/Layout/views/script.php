<?php

/**
 * @var View $this
 */

use ZnLib\Web\View\View;

if($this instanceof View) {
    foreach ($this->getJsFiles() as $item) {
        $options = $item['options'];
        $options['src'] = $item['file'];
        if(\ZnCore\Base\Libs\DotEnv\DotEnv::get('ASSET_FORCE_RELOAD', false)) {
            $options['src'] .= '?timestamp=' . time();
        }
        echo \ZnCore\Base\Legacy\Yii\Helpers\Html::tag('script', '', $options);
    }
    $this->resetJsFiles();
    ?>
    <script>
        jQuery(function ($) {
            <?= $this->getJsCode() ?>
        });
    </script>
    <?php
    $this->resetJsCode();
}
?>
