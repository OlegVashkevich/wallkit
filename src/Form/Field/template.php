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
 * @see Field::getWrapperClasses() Для получения CSS-классов обёртки
 * @see Field::getLabelId() Для получения ID поля для связи с меткой
 * @see Field::shouldShowPasswordToggle() Для проверки необходимости переключателя пароля
 * @see \OlegV\Traits\WithHelpers::hasString() Для проверки наличия строки
 * @see \OlegV\Traits\WithHelpers::classList() Для формирования строки CSS-классов
 * @see \OlegV\Traits\WithHelpers::e() Для безопасного экранирования вывода
 *
 * @package OlegV\WallKit\Form\Field
 * @author OlegV
 * @version 1.0.0
 *
 * @example
 * Рендерит структуру:
 * <div class="wallkit-field wallkit-field--error">
 *   <label for="email" class="wallkit-field__label">
 *     Email <span class="wallkit-field__required">*</span>
 *   </label>
 *   <div class="wallkit-field__wrapper">
 *     <input id="email" name="email" type="email" class="wallkit-input__field" required>
 *   </div>
 *   <div class="wallkit-field__error">⚠️ Некорректный email</div>
 * </div>
 */

use OlegV\WallKit\Form\Field\Field;

?>
<div class="<?= $this->e($this->classList($this->getWrapperClasses())) ?>">
    <?php
    // Внутри Field/template.php, после открывающего div.wallkit-field
    if (($this->input->type === 'radio' || $this->input->type === 'checkbox') && $this->hasString($this->label)):
        $wrapperClass = $this->input->type === 'radio' ? 'wallkit-field--radio' : 'wallkit-field--checkbox';
        $visualClass = $this->input->type === 'radio' ? 'wallkit-field__radio-visual' : 'wallkit-field__checkbox-visual';
        ?>
      <div class="wallkit-field__wrapper <?= $this->e($wrapperClass) ?>">
        <label for="<?= $this->e($this->input->id) ?>" class="wallkit-field__label">
            <?= $this->input ?>
          <span class="<?= $this->e($visualClass) ?>"></span>
            <?= $this->e($this->label) ?>
        </label>
      </div>
    <?php
    else: ?>
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
        if ($this->hasString($this->helpText) && ! $this->hasString($this->error)): ?>
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
    <?php
    endif; ?>
</div>