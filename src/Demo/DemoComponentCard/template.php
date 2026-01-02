<?php

declare(strict_types=1);

use OlegV\WallKit\Demo\DemoComponentCard\DemoComponentCard;

/** @var DemoComponentCard $this */
?>
<div class="wallkit-demo-component-card">
    <div class="wallkit-demo-component-card__header">
        <h3 class="wallkit-demo-component-card__title">
            <?= $this->e($this->title) ?>
        </h3>
        <span class="<?= $this->e($this->classList($this->getBadgeClasses())) ?>">
            <?= $this->e($this->badgeText) ?>
        </span>
    </div>

    <div class="wallkit-demo-component-card__preview">
        <?= $this->componentHtml ?>
    </div>

    <p class="wallkit-demo-component-card__description">
        <?= $this->e($this->description) ?>
    </p>

    <?php
    if ($this->note): ?>
        <div class="wallkit-demo-component-card__note">
            💡 <?= $this->e($this->note) ?>
        </div>
    <?php
    endif; ?>

    <pre class="wallkit-demo-component-card__code">
        <code><?= htmlspecialchars($this->code) ?></code>
    </pre>
</div>