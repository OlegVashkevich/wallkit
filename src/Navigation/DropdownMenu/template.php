<?php
/** @var OlegV\WallKit\Navigation\DropdownMenu\DropdownMenu $this */
?>
<div class="wallkit-dropdown-menu__container">
    <?= $this->renderTrigger() ?>

    <div <?= $this->attr($this->getMenuAttributes()) ?>>
        <ul class="wallkit-dropdown-menu__items" role="menu">
            <?php foreach ($this->items as $item): ?>
                <li class="wallkit-dropdown-menu__item" role="none">
                    <?= $item->render() ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>