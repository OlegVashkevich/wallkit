<?php

declare(strict_types=1);

/** @var DemoLayout $this */

use OlegV\WallKit\Demo\DemoLayout\DemoLayout;

?>
<div class="wallkit-demo-layout" style="
        grid-template-columns: <?= $this->e($this->getGridTemplate()) ?>;
        ">
    <?php
    if ($this->sidebarLeft): ?>
        <div class="wallkit-demo-layout__sidebar">
            <?= $this->sidebar ?>
        </div>
        <div class="wallkit-demo-layout__content">
            <?= $this->content ?>
        </div>
    <?php else: ?>
        <div class="wallkit-demo-layout__content">
            <?= $this->content ?>
        </div>
        <div class="wallkit-demo-layout__sidebar">
            <?= $this->sidebar ?>
        </div>
    <?php
    endif; ?>
</div>