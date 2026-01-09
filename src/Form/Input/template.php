<?php
/**
 * Шаблон для компонента Input
 *
 * Этот шаблон рендерит HTML-тег <input> с атрибутами, подготовленными компонентом Input.
 * Использует метод `$this->attr()` для безопасного вывода HTML-атрибутов.
 *
 * @var Input $this Экземпляр компонента Input
 * @see Input::getInputAttributes() Для получения атрибутов поля ввода
 * @see \OlegV\Traits\WithHelpers::attr() Для метода безопасного вывода атрибутов
 *
 * @package OlegV\WallKit\Form\Input
 * @author OlegV
 * @version 1.0.0
 *
 * @example
 * Рендерит:
 * <input id="username" name="username" type="text" class="wallkit-input__field" placeholder="Введите имя">
 */

use OlegV\WallKit\Form\Input\Input;

?>
<input <?= $this->attr($this->getInputAttributes()) ?>>