<?php
/**
 * Шаблон для компонента Select
 * Рендерит HTML-элемент <select> с опциями.
 *
 * @var Select $this Экземпляр компонента Select
 *
 * @package OlegV\WallKit\Form\Select
 * @author OlegV
 * @version 1.0.0
 *
 * @example
 * <select name="country" class="wallkit-select__field">
 *   <option value="ru">Россия</option>
 *   <option value="us">США</option>
 * </select>
 */

use OlegV\WallKit\Form\Select\Select;

// @formatter:off
?>
<select <?= $this->attr($this->getSelectAttributes()) ?>>
    <?php if ($this->placeholder): ?>
        <option value="" disabled <?= $this->selected === null ? 'selected' : '' ?>>
            <?= $this->e($this->placeholder) ?>
        </option>
    <?php endif?>
    <?php $currentGroup = null?>
    <?php foreach ($this->getNormalizedOptions() as $option):?>
        <?php if ($option['group'] !== $currentGroup):?>
            <?php if ($currentGroup !== null): ?>
                </optgroup>
            <?php endif?>
            <?php if ($option['group'] !== null): ?>
                <optgroup label="<?= $this->e($option['group']) ?>">
            <?php endif?>
            <?php $currentGroup = $option['group']?>
        <?php endif?>
        <option value="<?= $this->e($option['value']) ?>"
            <?= $this->isOptionSelected($option['value']) ? 'selected' : '' ?>>
            <?= $this->e($option['label']) ?>
        </option>
    <?php endforeach?>
    <?php if ($currentGroup !== null): ?>
        </optgroup>
    <?php endif?>
</select>
