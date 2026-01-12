<?php
/**
 * Шаблон для компонента Form
 *
 * Этот шаблон рендерит HTML-форму с полями, CSRF-токеном и атрибутами.
 *
 * @var Form $this Экземпляр компонента Form
 * @see Form::getFormAttributes() Для получения атрибутов формы
 *
 * @package OlegV\WallKit\Form\Form
 * @author OlegV
 * @version 1.0.0
 */

use OlegV\WallKit\Form\Form\Form;

?>
<form <?= $this->attr($this->getFormAttributes()) ?>>
  <?php if ($this->hasString($this->csrfToken) && in_array(
      strtoupper($this->method),
      ['POST', 'PUT', 'PATCH', 'DELETE'],
      true,
  )): ?>
    <input type="hidden" name="_token" value="<?= $this->e($this->csrfToken) ?>">
  <?php endif?>
  <?php foreach ($this->fields as $field): ?>
    <?= $field ?>
  <?php endforeach?>
  <!-- Место для динамических сообщений (заполняется JS) -->
  <div class="wallkit-form__messages"></div>
</form>