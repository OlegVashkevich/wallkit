<?php

declare(strict_types=1);

use OlegV\WallKit\Content\Code\Code;

/** @var Code $this */
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