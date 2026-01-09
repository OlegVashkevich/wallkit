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

$isRadioOrCheckbox = in_array($this->input->type, ['radio', 'checkbox']);
?>
  <div class="<?= $this->e($this->classList($this->getWrapperClasses())) ?>">
      <?php
      if ($isRadioOrCheckbox): ?>
        <label class="wallkit-field__label wallkit-field--<?= $this->e($this->input->type) ?>">
            <?= $this->input ?>
          <span class="wallkit-field__<?= $this->e($this->input->type) ?>-visual"></span>
          <span class="wallkit-field__label-text">
                <?= $this->e($this->label ?? '') ?>
                <?= $this->input->required ? '<span class="wallkit-field__required">*</span>' : '' ?>
            </span>
        </label>
      <?php
      else: ?>
          <?php
          if ($this->label): ?>
            <label for="<?= $this->e($this->getLabelId()) ?>" class="wallkit-field__label">
                <?= $this->e($this->label) ?>
                <?= $this->input->required ? '<span class="wallkit-field__required">*</span>' : '' ?>
            </label>
          <?php
          endif; ?>

        <div class="wallkit-field__wrapper">
            <?= $this->input ?>
            <?php
            if ($this->shouldShowPasswordToggle()): ?>
              <button type="button" class="wallkit-field__toggle-password" data-action="toggle-password">
                👁️
              </button>
            <?php
            endif; ?>
        </div>
      <?php
      endif; ?>

      <?php
      if ($this->helpText && !$this->error): ?>
        <div class="wallkit-field__help"><?= $this->e($this->helpText) ?></div>
      <?php
      endif; ?>

      <?php
      if ($this->error): ?>
        <div class="wallkit-field__error">
          <span>⚠️</span><span><?= $this->e($this->error) ?></span>
        </div>
      <?php
      endif; ?>
  </div>
<?php