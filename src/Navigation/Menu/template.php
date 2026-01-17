<?php
/** @var OlegV\WallKit\Navigation\Menu\Menu $this */
$attrs = $this->getMenuAttributes();
?>
<nav <?= $this->attr($attrs) ?>>
    <?php if ($this->hasString($this->brand)): ?>
        <a href="/" class="wallkit-menu__brand">
            <?= $this->e($this->brand) ?>
        </a>
    <?php endif ?>

    <?php if ($this->hasString($this->searchPlaceholder)): ?>
        <div class="wallkit-menu__search">
            <input type="search"
                   class="wallkit-menu__search-input"
                   placeholder="<?= $this->e($this->searchPlaceholder) ?>"
                   aria-label="Поиск в меню">
        </div>
    <?php endif ?>
    <ul class="wallkit-menu__items" role="menubar">
        <?php foreach ($this->items as $item): ?>
            <li class="wallkit-menu__item" role="none">
                <?= $item->render() ?>
            </li>
        <?php endforeach ?>
    </ul>
</nav>