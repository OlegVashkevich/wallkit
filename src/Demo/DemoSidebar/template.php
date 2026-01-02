<?php

declare(strict_types=1);

use OlegV\WallKit\Demo\DemoSidebar\DemoSidebar;

/** @var DemoSidebar $this */ ?>
<aside class="wallkit-demo-sidebar">
    <div class="wallkit-demo-sidebar__sticky">
        <?php
        if ($this->title): ?>
            <h3 class="wallkit-demo-sidebar__title">
                <i class="fas fa-bars"></i>
                <?= $this->e($this->title) ?>
            </h3>
        <?php
        endif; ?>

        <?php
        if (!empty($this->navItems)): ?>
            <nav class="wallkit-demo-sidebar__nav">
                <ul class="wallkit-demo-sidebar__nav-list">
                    <?php
                    foreach ($this->navItems as $index => $item): ?>
                        <?php
                        $attrs = $this->getNavItemAttributes($index); ?>
                        <li class="wallkit-demo-sidebar__nav-list-item">
                            <a
                                    class="<?= $this->e($this->classList($attrs['classes'])) ?>"
                                <?= $this->attr($attrs['attrs']) ?>
                            >
                                <span class="wallkit-demo-sidebar__nav-icon">
                                    <?= $this->e($item['icon']) ?>
                                </span>
                                <span class="wallkit-demo-sidebar__nav-text">
                                    <?= $this->e($item['title']) ?>
                                </span>
                            </a>
                        </li>
                    <?php
                    endforeach; ?>
                </ul>
            </nav>
        <?php
        endif; ?>

        <?php
        foreach ($this->infoCards as $card): ?>
            <div class="wallkit-demo-sidebar__info-card">
                <div class="wallkit-demo-sidebar__info-card-header">
                    <span class="wallkit-demo-sidebar__info-card-icon">
                        <?= $this->e($card['icon']) ?>
                    </span>
                    <h4 class="wallkit-demo-sidebar__info-card-title">
                        <?= $this->e($card['title']) ?>
                    </h4>
                </div>
                <p class="wallkit-demo-sidebar__info-card-content">
                    <?= $this->e($card['content']) ?>
                </p>
            </div>
        <?php
        endforeach; ?>
    </div>
</aside>