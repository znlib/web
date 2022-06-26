<?php

/**
 * @var View $this
 * @var \ZnLib\Web\Components\View\Resources\Css $css
 */

use ZnLib\Web\Components\View\Libs\View;

foreach ($css->getFiles() as $item) {
    $options = $item['options'];
    $options['rel'] = 'stylesheet';
    $options['href'] = $item['file'];
    if (\ZnCore\Base\DotEnv\Domain\Libs\DotEnv::get('ASSET_FORCE_RELOAD', false)) {
        $options['href'] .= '?timestamp=' . time();
    }
    echo \ZnLib\Web\Components\Html\Helpers\Html::tag('link', '', $options);
}
$css->resetFiles();
?>
<style>
    <?= $css->getCode() ?>
</style>
<?php
$css->resetCode();

?>