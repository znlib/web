<?php

/**
 * @var View $this
 * @var array $rows
 * @var string $type
 */

use ZnLib\Web\View\View;

$type = $type ?? 'td';

?>

<?php if ($rows): ?>
    <?php foreach ($rows as $row): ?>
        <tr>
            <?php foreach ($row as $cell): ?>
                <?php if ($type == 'td'): ?>
                    <td><?= $cell ?></td>
                <?php elseif ($type == 'th'): ?>
                    <th><?= $cell ?></th>
                <?php endif; ?>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>
