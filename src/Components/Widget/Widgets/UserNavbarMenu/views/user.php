<?php

/**
 * @var View $this
 * @var IdentityEntityInterface $identity
 * @var string $userMenuHtml
 */

use ZnCore\Contract\User\Interfaces\Entities\IdentityEntityInterface;
use ZnLib\Web\Helpers\Html;
use ZnLib\Components\I18Next\Facades\I18Next;
use ZnLib\Web\Components\View\Libs\View;

?>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
       aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-user"></i>
        <?= $identity->getUsername() ?>
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
        <?= $userMenuHtml ?>
        <a class="dropdown-item" href="#" onclick="$('#logout-form').submit()">
            <i class="fas fa-sign-out-alt"></i>
            <?= I18Next::t('user', 'auth.logout_title') ?>
        </a>
    </div>
</li>

<?= Html::beginForm(['/logout'], 'post', ['id' => 'logout-form']) . Html::endForm(); ?>
