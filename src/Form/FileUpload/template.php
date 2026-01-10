<?php
/**
 * Шаблон для компонента FileUpload
 *
 * Рендерит поле загрузки файлов с подписью, полем ввода и опциональными сообщениями.
 * Использует метод `$this->attr()` для безопасного вывода атрибутов.
 *
 * @var FileUpload $this Экземпляр компонента FileUpload
 *
 * @package OlegV\WallKit\Form\FileUpload
 * @author OlegV
 * @version 1.0.0
 */

use OlegV\WallKit\Form\FileUpload\FileUpload;

?>
<div class="wallkit-fileupload">
    <?php
    if ($this->label): ?>
      <label for="<?= $this->e($this->getId()) ?>" class="wallkit-fileupload__label">
          <?= $this->e($this->label) ?>
          <?php
          if ($this->required): ?>
            <span class="wallkit-fileupload__required" aria-hidden="true">*</span>
          <?php
          endif; ?>
      </label>
    <?php
    endif; ?>

  <div class="wallkit-fileupload__wrapper">
    <input <?= $this->attr($this->getInputAttributes()) ?>>

      <?php
      if ($this->placeholder): ?>
        <div class="wallkit-fileupload__placeholder">
            <?= $this->e($this->placeholder) ?>
        </div>
      <?php
      endif; ?>
  </div>

    <?php
    if ($this->helpText): ?>
      <div class="wallkit-fileupload__help">
          <?= $this->e($this->helpText) ?>
      </div>
    <?php
    endif; ?>

    <?php
    if ($this->error): ?>
      <div class="wallkit-fileupload__error">
          <?= $this->e($this->error) ?>
      </div>
    <?php
    endif; ?>
</div>