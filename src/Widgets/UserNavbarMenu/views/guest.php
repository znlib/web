<?php

/**
 * @var View $this
 * @var string $loginUrl
 */

use ZnCore\Base\I18Next\Facades\I18Next;
use ZnLib\Web\Helpers\Url;
use ZnLib\Web\View\View;

?>

<li class="nav-item">
    <a class="nav-link" href="<?= Url::to($loginUrl) ?>">
        <i class="fas fa-sign-in-alt"></i>
        <?= I18Next::t('user', 'auth.title') ?>
    </a>
</li>
