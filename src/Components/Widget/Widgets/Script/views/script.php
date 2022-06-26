<?php

/**
 * @var \ZnLib\Web\Components\View\Resources\Js $js
 */

foreach ($js->getFiles() as $item) {
    $options = $item['options'];
    $options['src'] = $item['file'];
    if (\ZnCore\Base\DotEnv\Domain\Libs\DotEnv::get('ASSET_FORCE_RELOAD', false)) {
        $options['src'] .= '?timestamp=' . time();
    }
    echo \ZnLib\Web\Helpers\Html::tag('script', '', $options);
}
$js->resetFiles();

?>
<script>
    jQuery(function ($) {
        <?= $js->getCode() ?>
    });
</script>
<?php
$js->resetCode();

?>
