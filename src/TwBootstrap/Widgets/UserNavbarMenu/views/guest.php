<?php

/**
 * @var View $this
 * @var string $loginUrl
 */

use ZnLib\Components\I18Next\Facades\I18Next;
use ZnLib\Web\Url\Helpers\Url;
use ZnLib\Web\View\Libs\View;

?>

<li class="nav-item">
    <a class="nav-link" href="<?= Url::to($loginUrl) ?>">
        <i class="fas fa-sign-in-alt"></i>
        <?= I18Next::t('user', 'auth.title') ?>
    </a>
</li>
