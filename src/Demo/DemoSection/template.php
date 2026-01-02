<?php

declare(strict_types=1);

use OlegV\WallKit\Demo\DemoSection\DemoSection;

/** @var DemoSection $this */ ?>
<section id="<?= $this->e($this->id) ?>" class="wallkit-demo-section">
    <div class="wallkit-demo-section__header">
        <div class="wallkit-demo-section__icon">
            <?= $this->e($this->icon) ?>
        </div>
        <div>
            <h2 class="wallkit-demo-section__title">
                <?= $this->e($this->title) ?>
            </h2>
            <p class="wallkit-demo-section__description">
                <?= $this->e($this->description) ?>
            </p>
        </div>
    </div>

    <?php
    if (!empty($this->componentCards)): ?>
        <div class="wallkit-demo-section__grid">
            <?php
            foreach ($this->componentCards as $card): ?>
                <?= $card ?>
            <?php
            endforeach; ?>
        </div>
    <?php
    endif; ?>

    <?php
    if ($this->extraContent): ?>
        <div class="wallkit-demo-section__extra">
            <?= $this->extraContent ?>
        </div>
    <?php
    endif; ?>
</section>