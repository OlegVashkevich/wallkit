<?php
/** @var OlegV\WallKit\Navigation\ContextMenu\ContextMenu $this */
?>
<div <?= $this->attr($this->getMenuAttributes()) ?>>
    <ul class="wallkit-context-menu__items" role="menu">
        <?php foreach ($this->items as $item): ?>
            <li class="wallkit-context-menu__item" role="none">
                <?= $item->render() ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>