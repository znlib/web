<?php

/**
 * @var View $this
 * @var \ZnLib\Web\View\Resources\Css $css
 */

use ZnLib\Web\View\View;

foreach ($css->getFiles() as $item) {
    $options = $item['options'];
    $options['rel'] = 'stylesheet';
    $options['href'] = $item['file'];
    if (\ZnCore\Base\Libs\DotEnv\DotEnv::get('ASSET_FORCE_RELOAD', false)) {
        $options['href'] .= '?timestamp=' . time();
    }
    echo \ZnCore\Base\Legacy\Yii\Helpers\Html::tag('link', '', $options);
}
$css->resetFiles();
?>
<style>
    <?= $css->getCode() ?>
</style>
<?php
$css->resetCode();

?>
