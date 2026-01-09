<?php
/**
 * Шаблон для компонента Checkbox
 *
 * Этот шаблон рендерит HTML-структуру чекбокса.
 *
 * @var Checkbox $this Экземпляр компонента Checkbox
 * @package OlegV\WallKit\Form\Checkbox
 * @author OlegV
 * @version 1.0.0
 */

use OlegV\WallKit\Form\Checkbox\Checkbox;

?>
<div class="<?= $this->e($this->classList($this->getContainerClasses())) ?>">
  <input <?= $this->attr($this->getInputAttributes()) ?>>

    <?php
    if ($this->label): ?>
      <label
        for="<?= $this->e($this->getLabelFor()) ?>"
        class="wallkit-checkbox__label"
      >
          <?= $this->e($this->label) ?>
      </label>
    <?php
    endif; ?>
</div>