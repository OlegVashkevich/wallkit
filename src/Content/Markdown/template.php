<?php
/**
 * Шаблон для компонента Markdown
 *
 * Этот шаблон рендерит контейнер для преобразованного Markdown-контента.
 * Использует метод `$this->toHtml()` для безопасного преобразования Markdown в HTML.
 *
 * @var Markdown $this Экземпляр компонента Markdown
 * @see Markdown::toHtml() Для преобразования Markdown в HTML
 * @see Markdown::toInlineHtml() Для инлайн-преобразования
 *
 * @package OlegV\WallKit\Content\Markdown
 * @author OlegV
 * @version 1.0.0
 *
 * @example
 * Рендерит:
 * <div class="wallkit-markdown">
 *   <h1>Заголовок</h1>
 *   <p>Абзац с <strong>жирным</strong> текстом</p>
 * </div>
 */

use OlegV\WallKit\Content\Markdown\Markdown;

?>
<div class="wallkit-markdown">
    <?= $this->toHtml() ?>
</div>