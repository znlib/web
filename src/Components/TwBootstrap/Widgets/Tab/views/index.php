<?php

/**
 * @var View $this
 * @var array $items
 * @var string $class
 */

use ZnLib\Web\Components\View\Libs\View;

?>

<ul class="nav nav-tabs <?= $class ?>">
    <?php foreach ($items as $item):
        $title = $item['title'];
        $isActive = $item['is_active'] ?? false;
        $itemClass = $isActive ? 'active' : '';
    ?>
        <li class="nav-item">
            <a class="nav-link <?= $itemClass ?>" href="<?= $item['url'] ?>" role="tab">
                <?= $title ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
