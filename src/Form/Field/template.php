<?php
/** @var Field $this */

use OlegV\WallKit\Form\Field\Field;

?>
<div class="<?= $this->e($this->classList($this->getWrapperClasses())) ?>">
    <?php
    if ($this->hasString($this->label)):
        $labelId = $this->getLabelId();
        ?>
        <?php
        if ($this->hasString($labelId)): ?>
            <label for="<?= $this->e($labelId) ?>" class="wallkit-field__label">
                <?= $this->e($this->label) ?>
                <?php
                if ($this->input->required): ?>
                    <span class="wallkit-field__required">*</span>
                <?php
                endif; ?>
            </label>
        <?php
        else: ?>
            <div class="wallkit-field__label">
                <?= $this->e($this->label) ?>
                <?php
                if ($this->input->required): ?>
                    <span class="wallkit-field__required">*</span>
                <?php
                endif; ?>
            </div>
        <?php
        endif; ?>
    <?php
    endif; ?>

    <div class="wallkit-field__wrapper">
        <?= $this->input ?>

        <?php
        if ($this->shouldShowPasswordToggle()): ?>
            <button type="button" class="wallkit-field__toggle-password"
                    aria-label="Показать/скрыть пароль">
                👁️
            </button>
        <?php
        endif; ?>
    </div>

    <?php
    if ($this->hasString($this->helpText) && !$this->hasString($this->error)): ?>
        <div class="wallkit-field__help">
            <?= $this->e($this->helpText) ?>
        </div>
    <?php
    endif; ?>

    <?php
    if ($this->hasString($this->error)): ?>
        <div class="wallkit-field__error">
            ⚠️ <?= $this->e($this->error) ?>
        </div>
    <?php
    endif; ?>
</div>