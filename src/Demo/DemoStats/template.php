<?php

declare(strict_types=1);

use OlegV\WallKit\Demo\DemoStats\DemoStats;

/** @var DemoStats $this */

?>
<div class="wallkit-demo-stats">
    <div class="wallkit-demo-stats__header">
        <h3 class="wallkit-demo-stats__title">📊 Статистика проекта</h3>
        <div class="wallkit-demo-stats__version">
            v<?= $this->e($this->latestVersion) ?>
        </div>
    </div>

    <div class="wallkit-demo-stats__progress">
        <div class="wallkit-demo-stats__progress-bar">
            <div
                    class="wallkit-demo-stats__progress-fill"
                    style="width: <?= $this->e((string) $this->getProgress()) ?>%"
            ></div>
        </div>
        <div class="wallkit-demo-stats__progress-text">
            <span class="wallkit-demo-stats__progress-percent">
                <?= round($this->getProgress()) ?>%
            </span>
            готово (<?= $this->stableComponents ?>/<?= $this->totalComponents ?> компонентов)
        </div>
    </div>

    <div class="wallkit-demo-stats__grid">
        <div class="wallkit-demo-stats__item wallkit-demo-stats__item--total">
            <div class="wallkit-demo-stats__item-value">
                <?= $this->totalComponents ?>
            </div>
            <div class="wallkit-demo-stats__item-label">
                Всего компонентов
            </div>
        </div>

        <div class="wallkit-demo-stats__item wallkit-demo-stats__item--stable">
            <div class="wallkit-demo-stats__item-value">
                <?= $this->stableComponents ?>
            </div>
            <div class="wallkit-demo-stats__item-label">
                Готовых
            </div>
        </div>

        <div class="wallkit-demo-stats__item wallkit-demo-stats__item--planned">
            <div class="wallkit-demo-stats__item-value">
                <?= $this->plannedComponents ?>
            </div>
            <div class="wallkit-demo-stats__item-label">
                В планах
            </div>
        </div>

        <div class="wallkit-demo-stats__item wallkit-demo-stats__item--demos">
            <div class="wallkit-demo-stats__item-value">
                <?= $this->demoPages ?>
            </div>
            <div class="wallkit-demo-stats__item-label">
                Демо-страниц
            </div>
        </div>
    </div>
</div>