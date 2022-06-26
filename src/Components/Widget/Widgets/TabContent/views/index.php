<?php

/**
 * @var View $this
 * @var array $items
 * @var string $contentClass
 */

use ZnLib\Web\Components\View\Libs\View;

?>

<ul class="nav nav-tabs" id="myTab" role="tablist">
    <?php foreach ($items as $item):
        $name = $item['name'];
        $title = $item['title'];
        $isActive = $item['is_active'] ?? false;
        $itemClass = $isActive ? 'active' : '';
    ?>
        <li class="nav-item" role="presentation">
            <a class="nav-link <?= $itemClass ?>" id="<?= $name ?>-tab" data-toggle="tab" href="#<?= $name ?>" role="tab" aria-controls="<?= $name ?>"
               aria-selected="true"><?= $title ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<div class="tab-content <?= $contentClass ?>" id="myTabContent">
    <?php foreach ($items as $item):
        $name = $item['name'];
        $title = $item['title'];
        $isActive = $item['is_active'] ?? false;
        $itemClass = $isActive ? 'active' : '';
        ?>
        <div class="tab-pane fade show <?= $itemClass ?>" id="<?= $name ?>" role="tabpanel" aria-labelledby="<?= $name ?>-tab">
            <?= $item['content'] ?>
        </div>
    <?php endforeach; ?>
</div>
