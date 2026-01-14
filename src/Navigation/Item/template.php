<?php
/** @var OlegV\WallKit\Navigation\Item\Item $this */
$level = $level ?? 1;
$renderChildren = $renderChildren ?? true;
?>
<?php if ($this->type === 'divider'): ?>
    <div class="wallkit-item wallkit-item--divider" role="separator"></div>
<?php elseif ($this->type === 'header'): ?>
    <div class="wallkit-item wallkit-item--header">
        <?php if ($this->icon): ?>
            <span class="wallkit-item__icon"><?= $this->e($this->icon) ?></span>
        <?php endif; ?>
        <span class="wallkit-item__label"><?= $this->e($this->label) ?></span>
    </div>
<?php else: ?>
    <?php
    $tag = $this->getTag();
    $attrs = $this->getAttributes();
    ?>
    <div class="wallkit-item__wrapper wallkit-item__wrapper--level-<?= $level ?>" data-has-children="<?= $this->hasChildren() ? 'true' : 'false' ?>">
        <<?= $tag ?> <?= $this->attr($attrs) ?>>
            <?php if ($this->icon): ?>
                <span class="wallkit-item__icon"><?= $this->e($this->icon) ?></span>
            <?php endif; ?>

            <span class="wallkit-item__label"><?= $this->e($this->label) ?></span>

            <?php if ($this->badge): ?>
                <span class="wallkit-item__badge"><?= $this->e($this->badge) ?></span>
            <?php endif; ?>

            <?php if ($this->hasChildren()): ?>
                <span class="wallkit-item__arrow">▶</span>
            <?php endif; ?>
        </<?= $tag ?>>

        <?php if ($this->hasChildren() && $renderChildren): ?>
            <div class="wallkit-item__children">
                <?php foreach ($this->children as $child): ?>
                    <?= $child->render(['level' => $level + 1, 'renderChildren' => $renderChildren]) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>