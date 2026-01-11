<?php
/**
 * Шаблон для компонента TagCloud
 *
 * Этот шаблон рендерит HTML-структуру облака тегов. Каждый тег отображается
 * либо как активная ссылка (если указан URL), либо как неактивный элемент.
 * Размер тегов определяется CSS-классами, назначенными компонентом.
 *
 * @var TagCloud $this Экземпляр компонента TagCloud
 * @see TagCloud::getProcessedTags() Для получения подготовленных тегов
 * @see TagCloud::assignSizeClasses() Для логики назначения CSS-классов размера
 * @see \OlegV\Traits\Mold::e() Для безопасного экранирования вывода
 *
 * @package OlegV\WallKit\Content\TagCloud
 * @author OlegV
 * @version 1.0.0
 * @since 1.0.0
 */

use OlegV\WallKit\Content\TagCloud\TagCloud;

?>
<div class="wallkit-tagcloud">
    <?php
    foreach ($this->getProcessedTags() as $tag): ?>
        <?php
        if ($this->hasString($tag['url'])): ?>
          <a href="<?= $this->e($tag['url']) ?>"
             class="wallkit-tagcloud__tag <?= $this->e($tag['sizeClass'] ?? '') ?>">
              <?= $this->e($tag['label']) ?>
          </a>
        <?php else: ?>
          <span class="wallkit-tagcloud__tag <?= $this->e($tag['sizeClass'] ?? '') ?>">
                <?= $this->e($tag['label']) ?>
            </span>
        <?php
        endif; ?>
    <?php
    endforeach; ?>
</div>