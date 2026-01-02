<?php

declare(strict_types=1);

use OlegV\WallKit\Demo\DemoComponentGrid\DemoComponentGrid;

/** @var DemoComponentGrid $this */

?>
    <div class="wallkit-demo-component-grid">
        <?php
        if ($this->showGroups): ?>
            <?php
            foreach ($this->getComponentsByGroup() as $groupName => $groupComponents): ?>
                <div
                        class="wallkit-demo-component-grid__group"
                        data-group="<?= $this->e(strtolower($groupName)) ?>"
                        style="border-left-color: <?= $this->e($this->getGroupColor($groupName)) ?>;"
                >
                    <h3 class="wallkit-demo-component-grid__group-title">
                    <span
                            class="wallkit-demo-component-grid__group-icon"
                            style="background: <?= $this->e($this->getGroupColor($groupName)) ?>20;"
                    >
                        <?= $this->e($this->getGroupIcon($groupName)) ?>
                    </span>
                        <?= $this->e($this->getGroupTitle($groupName)) ?>
                        <span class="wallkit-demo-component-grid__group-count">
                        <?= count($groupComponents) ?>
                    </span>
                    </h3>

                    <?php
                    if ($groupDescription = $this->getGroupDescription($groupName)): ?>
                        <p class="wallkit-demo-component-grid__group-description">
                            <?= $this->e($groupDescription) ?>
                        </p>
                    <?php
                    endif; ?>

                    <div class="wallkit-demo-component-grid__items">
                        <?php
                        foreach ($groupComponents as $component): ?>
                            <a
                                    href="/examples/<?= $this->e($component['demoFile']) ?>"
                                    class="wallkit-demo-component-grid__item"
                                    data-status="<?= $this->e($component['status']) ?>"
                                    data-badge="<?= $this->e($component['badge']) ?>"
                                    data-tags="<?= $this->e(implode(', ', $component['tags'] ?? [])) ?>"
                                <?php
                                if ($component['status'] === 'planned'): ?>
                                    aria-disabled="true"
                                <?php
                                endif; ?>
                            >
                                <div class="wallkit-demo-component-grid__item-header">
                                    <div
                                            class="wallkit-demo-component-grid__item-icon"
                                            style="background: <?= $this->e($this->getGroupColor($groupName)) ?>10;"
                                    >
                                        <?= $this->e($component['icon']) ?>
                                    </div>
                                    <div class="wallkit-demo-component-grid__item-info">
                                        <h4 class="wallkit-demo-component-grid__item-name">
                                            <?= $this->e($component['name']) ?>
                                        </h4>
                                        <?php
                                        if ($this->showStatus): ?>
                                            <span class="<?= $this->e(
                                                $this->classList($this->getStatusClasses($component['status'])),
                                            ) ?>">
                                            <?= $this->e($component['status']) ?>
                                        </span>
                                        <?php
                                        endif; ?>
                                    </div>
                                </div>

                                <p class="wallkit-demo-component-grid__item-description">
                                    <?= $this->e($component['description']) ?>
                                </p>

                                <div class="wallkit-demo-component-grid__item-footer">
                                    <?php
                                    if (!empty($component['tags'])): ?>
                                        <div class="wallkit-demo-component-grid__item-tags">
                                            <?php
                                            foreach ($component['tags'] as $tag): ?>
                                                <span class="wallkit-demo-component-grid__item-tag">
                                                <?= $this->e($tag) ?>
                                            </span>
                                            <?php
                                            endforeach; ?>
                                        </div>
                                    <?php
                                    endif; ?>

                                    <?php
                                    if ($component['since']): ?>
                                        <span class="wallkit-demo-component-grid__item-version">
                                        v<?= $this->e($component['since']) ?>
                                    </span>
                                    <?php
                                    endif; ?>
                                </div>
                            </a>
                        <?php
                        endforeach; ?>
                    </div>
                </div>
            <?php
            endforeach; ?>
        <?php
        else: ?>
            <!-- Плоский список без группировки -->
            <div class="wallkit-demo-component-grid__items">
                <?php
                foreach ($this->components as $component): ?>
                    <a
                            href="/examples/<?= $this->e($component['demoFile']) ?>"
                            class="wallkit-demo-component-grid__item"
                    >
                        <div class="wallkit-demo-component-grid__item-header">
                            <div class="wallkit-demo-component-grid__item-icon">
                                <?= $this->e($component['icon']) ?>
                            </div>
                            <div class="wallkit-demo-component-grid__item-info">
                                <h4 class="wallkit-demo-component-grid__item-name">
                                    <?= $this->e($component['name']) ?>
                                </h4>
                            </div>
                        </div>

                        <p class="wallkit-demo-component-grid__item-description">
                            <?= $this->e($component['description']) ?>
                        </p>

                        <div class="wallkit-demo-component-grid__item-footer">
                        <span class="wallkit-demo-component-grid__item-group">
                            <?= $this->e($component['group']) ?>
                        </span>
                        </div>
                    </a>
                <?php
                endforeach; ?>
            </div>
        <?php
        endif; ?>
    </div>
<?php
// Получаем все теги
$allTags = $this->getAllTags();

if (!empty($allTags)):
    ?>
    <div class="wallkit-demo-component-grid__tags-cloud">
        <h4 class="wallkit-demo-component-grid__tags-title">Облако тегов</h4>
        <div class="wallkit-demo-component-grid__tags-list">
            <?php
            foreach ($allTags as $tag => $count): ?>
                <a
                        href="#"
                        class="wallkit-demo-component-grid__tag <?= $this->e($this->getTagSizeClass($count)) ?>"
                        data-count="<?= $this->e((string)$count) ?>"
                        onclick="filterByTag('<?= $this->e($tag) ?>'); return false;"
                >
                    <?= $this->e($tag) ?>
                    <?php
                    if ($count > 1): ?>
                        <span class="wallkit-demo-component-grid__tag-count">
                        <?= $this->e((string)$count) ?>
                    </span>
                    <?php
                    endif; ?>
                </a>
            <?php
            endforeach; ?>
        </div>
    </div>
<?php
endif; ?>