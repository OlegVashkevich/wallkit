<?php
/** @var OlegV\WallKit\Navigation\Menu\Menu $this */
$attrs = $this->getMenuAttributes();
?>
<nav <?= $this->attr($attrs) ?>>
    <?php if ($this->brand): ?>
        <div class="wallkit-menu__brand">
            <?= $this->e($this->brand) ?>
        </div>
    <?php endif; ?>

    <?php if ($this->searchPlaceholder): ?>
        <div class="wallkit-menu__search">
            <input type="search"
                   class="wallkit-menu__search-input"
                   placeholder="<?= $this->e($this->searchPlaceholder) ?>"
                   aria-label="Поиск">
        </div>
    <?php endif; ?>

    <ul class="wallkit-menu__items">
        <?= $this->renderItems() ?>
    </ul>
</nav>