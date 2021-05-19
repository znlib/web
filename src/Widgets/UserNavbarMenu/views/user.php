<?php

/**
 * @var \ZnLib\Web\View\View $this
 * @var \ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface $identity
 * @var string $userMenuHtml
 */

use ZnCore\Base\Legacy\Yii\Helpers\Html;
use ZnCore\Base\Legacy\Yii\Helpers\Url;
use ZnCore\Base\Libs\I18Next\Facades\I18Next;

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
