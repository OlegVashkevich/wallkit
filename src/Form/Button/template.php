<?php
/**
 * Шаблон для компонента Button
 *
 * Этот шаблон рендерит либо <button>, либо <a> в зависимости от наличия href.
 * Поддерживает иконки до и после текста.
 *
 * @var Button $this Экземпляр компонента Button
 * @see Button::getButtonAttributes() Для получения атрибутов кнопки
 * @see \OlegV\Traits\WithHelpers::attr() Для безопасного вывода атрибутов
 *
 * @package OlegV\WallKit\Form\Button
 * @author OlegV
 * @version 1.0.0
 */

use OlegV\WallKit\Form\Button\Button;

// @formatter:off
?>
<?php if ($this->isLink()): ?>
    <a <?= $this->attr($this->getButtonAttributes()) ?>>
        <?php if ($this->hasString($this->icon)): ?>
            <span class="wallkit-button__icon"><?= $this->e($this->icon) ?></span>
        <?php endif; ?>

        <span class="wallkit-button__text"><?= $this->e($this->text) ?></span>

        <?php if ($this->hasString($this->iconAfter)): ?>
            <span class="wallkit-button__icon wallkit-button__icon--after"><?= $this->e($this->iconAfter) ?></span>
        <?php endif; ?>
    </a>
<?php else: ?>
    <button <?= $this->attr($this->getButtonAttributes()) ?>>
        <?php if ($this->hasString($this->icon)): ?>
            <span class="wallkit-button__icon"><?= $this->e($this->icon) ?></span>
        <?php endif; ?>

        <span class="wallkit-button__text"><?= $this->e($this->text) ?></span>

        <?php if ($this->hasString($this->iconAfter)): ?>
            <span class="wallkit-button__icon wallkit-button__icon--after"><?= $this->e($this->iconAfter) ?></span>
        <?php endif; ?>
    </button>
<?php endif; ?>