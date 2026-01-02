<?php

declare(strict_types=1);

use OlegV\WallKit\Demo\DemoFormExample\DemoFormExample;

/** @var DemoFormExample $this */ ?>
<div class="wallkit-demo-form-example">
    <div class="wallkit-demo-form-example__header">
        <h3 class="wallkit-demo-form-example__title">
            <?= $this->e($this->title) ?>
        </h3>
        <p class="wallkit-demo-form-example__description">
            <?= $this->e($this->description) ?>
        </p>
    </div>

    <div class="wallkit-demo-form-example__form">
        <?= $this->formHtml ?>
    </div>

    <?php
    if (!empty($this->actions)): ?>
        <div class="wallkit-demo-form-example__actions">
            <?php
            foreach ($this->actions as $action): ?>
                <button type="button" <?= $this->attr($this->getActionClasses($action['variant'])) ?>>
                    <?php
                    if ($action['icon'] ?? false): ?>
                        <span class="wallkit-demo-form-example__action-icon">
                            <?= $this->e($action['icon']) ?>
                        </span>
                    <?php
                    endif; ?>
                    <?= $this->e($action['text']) ?>
                </button>
            <?php
            endforeach; ?>
        </div>
    <?php
    endif; ?>

    <?php
    if (!empty($this->notes)): ?>
        <div class="wallkit-demo-form-example__notes">
            <?php
            foreach ($this->notes as $type => $note): ?>
                <div class="wallkit-demo-form-example__note wallkit-demo-form-example__note--<?= $this->e($type) ?>">
                    <?php
                    if ($type === 'tip'): ?>💡<?php
                    endif; ?>
                    <?php
                    if ($type === 'warning'): ?>⚠️<?php
                    endif; ?>
                    <?php
                    if ($type === 'info'): ?>ℹ️<?php
                    endif; ?>
                    <?= $this->e($note) ?>
                </div>
            <?php
            endforeach; ?>
        </div>
    <?php
    endif; ?>
</div>