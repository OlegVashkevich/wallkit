<?php
/**
 * Шаблон для компонента Field
 *
 * Этот шаблон рендерит обёртку для поля ввода с меткой, подсказкой, ошибкой
 * и опциональным переключателем видимости пароля.
 *
 * Структура шаблона:
 * 1. Обёртка (div.wallkit-field) — содержит всё поле
 * 2. Метка (label/div.wallkit-field__label) — опционально, с индикатором обязательности
 * 3. Внутренний враппер для поля и переключателя пароля
 * 4. Поле ввода (рендерится через $this->input)
 * 5. Переключатель пароля (только для type="password" с опцией)
 * 6. Текст помощи (если нет ошибки)
 * 7. Сообщение об ошибке (если есть)
 *
 * @var Field $this Экземпляр компонента Field
 *
 * @package OlegV\WallKit\Form\Field
 * @author OlegV
 * @version 1.0.0
 */

use OlegV\WallKit\Form\Field\Field;

$fieldType = $this->getFieldType();
$isCheckable = $this->isCheckable();
?>

<div class="<?= $this->e($this->classList($this->getWrapperClasses())) ?>">
    <?php
    if ($this->hasString($this->label)): ?>
        <?php
        if ($isCheckable): ?>
          <!-- Radio/Checkbox: label оборачивает всё -->
          <label class="wallkit-field__label wallkit-field--<?= $this->e($fieldType) ?>">
              <?= $this->input ?>
            <span class="wallkit-field__<?= $this->e($fieldType) ?>-visual"></span>
            <span class="wallkit-field__label-text">
                    <?= $this->e($this->label) ?>
                    <?= $this->input->required ? '<span class="wallkit-field__required">*</span>' : '' ?>
                </span>
          </label>
        <?php
        else: ?>
          <!-- Все остальные поля: label оборачивает всё -->
          <label class="wallkit-field__label">
            <span class="wallkit-field__label-text">
                <?= $this->e($this->label) ?>
                <?= $this->input->required ? '<span class="wallkit-field__required">*</span>' : '' ?>
            </span>
            <span class="wallkit-field__wrapper">
              <?= $this->input ?>
                <?php
                if ($this->shouldShowPasswordToggle()): ?>
                  <button type="button" class="wallkit-field__toggle-password"
                          aria-label="Показать/скрыть пароль">
                  👁️
                </button>
                <?php
                endif; ?>
          </span>
          </label>
        <?php
        endif; ?>
    <?php
    else: ?>
      <!-- Поле без label -->
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
    endif; ?>

    <?php
    if ($this->hasString($this->helpText) && !$this->hasString($this->error)): ?>
      <div class="wallkit-field__help"><?= $this->e($this->helpText) ?></div>
    <?php
    endif; ?>

    <?php
    if ($this->hasString($this->error)): ?>
      <div class="wallkit-field__error" role="alert">
        <span>⚠️</span><span><?= $this->e($this->error) ?></span>
      </div>
    <?php
    endif; ?>
</div>