<?php
/** @var Input $this */

use OlegV\WallKit\Form\Input\Input;

?>
<div class="<?= $this->e($this->classList($this->getBaseClasses())) ?>">
    <?php
    if ($this->hasString($this->label) && $this->hasString($this->id)): ?>
        <label for="<?= $this->e($this->id) ?>" class="wallkit-input__label">
            <?= $this->e($this->label) ?>
            <?php
            if ($this->required): ?>
                <span class="wallkit-input__required">*</span>
            <?php
            endif; ?>
        </label>
    <?php
    elseif ($this->hasString($this->label)): ?>
        <div class="wallkit-input__label">
            <?= $this->e($this->label) ?>
            <?php
            if ($this->required): ?>
                <span class="wallkit-input__required">*</span>
            <?php
            endif; ?>
        </div>
    <?php
    endif; ?>

    <div class="wallkit-input__wrapper">
        <input <?= $this->attr($this->getInputAttributes()) ?>>

        <?php
        if ($this->type === 'password' && $this->withPasswordToggle): ?>
            <button type="button" class="wallkit-input__toggle-password"
                    aria-label="Показать/скрыть пароль">
                👁️
            </button>
        <?php
        endif; ?>
    </div>

    <?php
    if ($this->hasString($this->helpText) && !$this->hasString($this->error)): ?>
        <div class="wallkit-input__help">
            <?= $this->e($this->helpText) ?>
        </div>
    <?php
    endif; ?>

    <?php
    if ($this->hasString($this->error)): ?>
        <div class="wallkit-input__error">
            ⚠️ <?= $this->e($this->error) ?>
        </div>
    <?php
    endif; ?>
</div>