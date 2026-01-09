<?php

declare(strict_types=1);

/**
 * Шаблон для компонента Code
 *
 * Этот шаблон рендерит HTML-блок для отображения исходного кода с поддержкой:
 * - Подсветки синтаксиса через highlight.php
 * - Нумерации строк
 * - Кнопки копирования кода в буфер обмена
 * - Метки с названием языка программирования
 *
 * @var Code $this Экземпляр компонента Code
 * @see Code::getHighlightedContent() Для получения подсвеченного содержимого кода
 * @see \OlegV\Traits\WithHelpers::e() Для безопасного вывода строк в HTML
 *
 * @package OlegV\WallKit\Content\Code
 * @author OlegV
 * @version 1.0.0
 *
 * @example
 * Простой блок кода:
 * <div class="wallkit-code" data-language="javascript">
 *   <pre><code class="hljs language-javascript">console.log("Hello")</code></pre>
 * </div>
 *
 * @example
 * Полный блок с заголовком и номерами строк:
 * <div class="wallkit-code" data-language="php">
 *   <div class="wallkit-code__header">
 *     <span class="wallkit-code__language">php</span>
 *     <button class="wallkit-code__copy-button">Копировать</button>
 *   </div>
 *   <div class="wallkit-code__content">
 *     <div class="wallkit-code__lines">1 2 3</div>
 *     <pre><code class="hljs language-php">&lt;?php echo "Hello" ?&gt;</code></pre>
 *   </div>
 * </div>
 */

use OlegV\WallKit\Content\Code\Code;

?>
<div class="wallkit-code" data-language="<?= $this->e($this->language) ?>">
    <?php
    if ($this->showLanguage || $this->copyButton): ?>
        <div class="wallkit-code__header">
            <?php
            if ($this->showLanguage): ?>
                <span class="wallkit-code__language">
                    <?= $this->e($this->language) ?>
                </span>
            <?php
            endif; ?>

            <?php
            if ($this->copyButton): ?>
                <button
                        class="wallkit-code__copy-button"
                        type="button"
                        data-action="copy-code"
                        data-copied-text="Скопировано!"
                >
                    Копировать
                </button>
            <?php
            endif; ?>
        </div>
    <?php
    endif; ?>

    <div class="wallkit-code__content">
        <?php
        if ($this->lineNumbers): ?>
            <div class="wallkit-code__lines">
                <?php
                $lines = explode("\n", $this->content);
                foreach ($lines as $i => $line):
                    ?>
                    <span class="wallkit-code__line-number"><?= $i + 1 ?></span>
                <?php
                endforeach; ?>
            </div>
        <?php
        endif; ?>

        <pre><code class="<?= $this->highlight ? 'hljs language-'.$this->e(
                    $this->language,
                ) : '' ?>"><?= $this->getHighlightedContent() ?>
        </code></pre>
    </div>
</div>