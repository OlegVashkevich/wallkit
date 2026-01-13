<?php
/**
 * Шаблон для компонента Menu
 *
 * Рендерит универсальное меню с поддержкой всех вариантов.
 *
 * @var Menu $this
 */

use OlegV\WallKit\Navigation\Menu\Menu;

/**
 * Рекурсивно рендерит элементы меню
 */
$renderItems = function(array $items, int $level = 1) use (&$renderItems, &$menu) {
    $html = '';
    foreach ($items as $item) {
        $hasChildren = !empty($item['children']);

        $html .= '<li class="' . $menu->classList($menu->getItemClasses($item, $level)) . '" role="none">';

        // Определяем тег и атрибуты
        if ($item['action']) {
            $tag = 'button';
            $attrs = 'type="button" data-action="' . $menu->e($item['action']) . '"';
        } else {
            $tag = 'a';
            $attrs = 'href="' . $menu->e($item['url'] ?? '#') . '"';
        }

        if ($item['disabled']) {
            $attrs .= ' aria-disabled="true"';
        }

        if ($hasChildren) {
            $attrs .= ' aria-haspopup="true" aria-expanded="false"';
        }

        $html .= "<$tag class=\"wallkit-menu__link\" $attrs role=\"menuitem\">";

        // Иконка
        if ($item['icon']) {
            $html .= '<span class="wallkit-menu__icon">' . $menu->e($item['icon']) . '</span>';
        }

        // Текст
        $html .= '<span class="wallkit-menu__label">' . $menu->e($item['label']) . '</span>';

        // Стрелка для вложенных элементов
        if ($hasChildren) {
            $html .= '<span class="wallkit-menu__arrow">▶</span>';
        }

        $html .= "</$tag>";

        // Вложенные элементы
        if ($hasChildren) {
            $html .= '<ul class="wallkit-menu__submenu" role="menu">';
            $html .= $renderItems($item['children'], $level + 1);
            $html .= '</ul>';
        }

        $html .= '</li>';
    }
    return $html;
};

$menu = $this;
?>
<?php if (in_array($this->variant, ['navbar', 'sidebar'], true)): ?>
    <<?= $this->variant === 'navbar' ? 'nav' : 'aside' ?> <?= $this->attr($this->getMenuAttributes()) ?>>
        <div class="wallkit-menu__container">
            <?php if ($this->brand !== null): ?>
                <div class="wallkit-menu__brand">
                    <a href="/" class="wallkit-menu__brand-link">
                        <?= $this->e($this->brand) ?>
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($this->searchPlaceholder !== null): ?>
                <div class="wallkit-menu__search">
                    <input
                        type="search"
                        class="wallkit-menu__search-input"
                        placeholder="<?= $this->e($this->searchPlaceholder) ?>"
                        aria-label="Поиск"
                    >
                </div>
            <?php endif; ?>

            <ul class="wallkit-menu__list" role="menu">
                <?= $renderItems($this->getPreparedItems()) ?>
            </ul>

            <?php if ($this->collapsible): ?>
                <button
                    class="wallkit-menu__toggler"
                    type="button"
                    aria-label="Переключить меню"
                    data-menu-toggle
                >
                    <span class="wallkit-menu__toggler-icon"></span>
                </button>
            <?php endif; ?>
        </div>
    </<?= $this->variant === 'navbar' ? 'nav' : 'aside' ?>>
<?php else: ?>
    <div <?= $this->attr($this->getMenuAttributes()) ?>>
        <ul class="wallkit-menu__list" role="menu">
            <?= $renderItems($this->getPreparedItems()) ?>
        </ul>
    </div>
<?php endif; ?>