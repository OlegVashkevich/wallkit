<?php

declare(strict_types=1);

use OlegV\WallKit\Demo\DemoHeader\DemoHeader;

/** @var DemoHeader $this */ ?>
<header class="wallkit-demo-header">
    <h1 class="wallkit-demo-header__title">
        <?php
        if ($this->icon): ?>
            <span class="wallkit-demo-header__icon"><?= $this->e($this->icon) ?></span>
        <?php
        endif; ?>
        <?= $this->e($this->title) ?>
    </h1>
    <p class="wallkit-demo-header__subtitle">
        <?= $this->e($this->subtitle) ?>
    </p>
</header>