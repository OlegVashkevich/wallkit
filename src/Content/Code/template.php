<?php

declare(strict_types=1);

use OlegV\WallKit\Content\Code\Code;

/** @var Code $this */
?>
<?php
if ($this->inline): ?>
    <code class="wallkit-code wallkit-code--inline language-<?= $this->e($this->language) ?>">
        <?= $this->e($this->content) ?>
    </code>
<?php
else: ?>
    <pre
            class="wallkit-code wallkit-code--block <?= $this->showLineNumbers ? 'line-numbers' : '' ?>"
    ><code
                class="language-<?= $this->e($this->language) ?>"
        ><?= $this->e($this->content) ?></code>
    </pre>
<?php
endif; ?>