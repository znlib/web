<?php

/**
 * @var View $this
 * @var string $tableClass
 * @var array $headerRow
 * @var array $bodyRows
 */

use ZnLib\Web\View\View;

?>

<table class="<?= $tableClass ?>">
    <?php if ($headerRow): ?>
        <thead>
        <?= $this->renderFile(__DIR__ . '/rows.php', ['rows' => [$headerRow], 'type' => 'th']) ?>
        </thead>
    <?php endif; ?>
    <?php if ($bodyRows): ?>
        <tbody>
        <?= $this->renderFile(__DIR__ . '/rows.php', ['rows' => $bodyRows]) ?>
        </tbody>
    <?php endif; ?>
</table>
