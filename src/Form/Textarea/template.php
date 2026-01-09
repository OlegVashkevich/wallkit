<?php
/**
 * Шаблон для компонента Textarea
 *
 * Этот шаблон рендерит HTML-тег <textarea> с атрибутами, подготовленными компонентом Textarea.
 * Использует метод `$this->attr()` для безопасного вывода HTML-атрибутов и `$this->e()`
 * для экранирования содержимого текстовой области.
 *
 * @var Textarea $this Экземпляр компонента Textarea
 * @see Textarea::getTextareaAttributes() Для получения атрибутов текстовой области
 * @see Textarea::$value Для текущего значения поля
 * @see \OlegV\Traits\WithHelpers::attr() Для метода безопасного вывода атрибутов
 * @see \OlegV\Traits\WithHelpers::e() Для метода безопасного экранирования вывода
 *
 * @package OlegV\WallKit\Form\Textarea
 * @author OlegV
 * @version 1.0.0
 *
 * @example
 * Рендерит:
 * <textarea id="comment" name="comment" class="wallkit-textarea__field"
 *           rows="4" placeholder="Введите комментарий">Текущий текст</textarea>
 */

use OlegV\WallKit\Form\Textarea\Textarea; ?>
<textarea <?= $this->attr($this->getTextareaAttributes()) ?>><?= $this->e($this->value) ?></textarea>