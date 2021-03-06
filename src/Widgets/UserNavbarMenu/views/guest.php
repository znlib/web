<?php

/**
 * @var View $this
 * @var string $loginUrl
 */

use ZnCore\Base\Libs\I18Next\Facades\I18Next;
use ZnCore\Base\Legacy\Yii\Helpers\Url;
use ZnLib\Web\View\View;

?>

<li class="nav-item">
    <a class="nav-link" href="<?= Url::to($loginUrl) ?>">
        <i class="fas fa-sign-in-alt"></i>
        <?= I18Next::t('user', 'auth.title') ?>
    </a>
</li>
