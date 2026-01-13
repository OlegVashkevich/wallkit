<?php

declare(strict_types=1);

namespace OlegV\WallKit\Navigation\Menu;

use InvalidArgumentException;
use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithInheritance;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;

/**
 * Компонент Menu — универсальное меню для навигации и действий
 *
 * Поддерживает все варианты меню: горизонтальное (navbar), вертикальное (sidebar),
 * выпадающее (dropdown) и контекстное (context).
 *
 * ## Примеры использования
 *
 * ### Горизонтальное меню (Navbar)
 * ```php
 * $menu = new Menu(
 *     items: [
 *         ['label' => 'Главная', 'url' => '/'],
 *         ['label' => 'О нас', 'url' => '/about'],
 *         ['label' => 'Контакты', 'url' => '/contact'],
 *     ],
 *     orientation: 'horizontal',
 *     variant: 'navbar',
 *     brand: 'МойСайт',
 *     position: 'top',
 * );
 * echo $menu;
 * ```
 *
 * ### Вертикальное меню (Sidebar)
 * ```php
 * $menu = new Menu(
 *     items: [
 *         [
 *             'label' => 'Дашборд',
 *             'icon' => '📊',
 *             'url' => '/dashboard',
 *             'active' => true,
 *         ],
 *         [
 *             'label' => 'Пользователи',
 *             'icon' => '👥',
 *             'children' => [
 *                 ['label' => 'Список', 'url' => '/users'],
 *                 ['label' => 'Добавить', 'url' => '/users/new'],
 *             ],
 *         ],
 *     ],
 *     orientation: 'vertical',
 *     variant: 'sidebar',
 *     position: 'left',
 *     collapsible: true,
 * );
 * ```
 *
 * ### Выпадающее меню
 * ```php
 * $menu = new Menu(
 *     items: [
 *         ['label' => 'Редактировать', 'action' => 'edit'],
 *         ['label' => 'Удалить', 'action' => 'delete', 'danger' => true],
 *     ],
 *     orientation: 'vertical',
 *     variant: 'dropdown',
 *     position: 'floating',
 *     trigger: 'click',
 * );
 * ```
 *
 * @package OlegV\WallKit\Navigation\Menu
 * @author OlegV
 * @since 1.0.0
 * @version 1.0.0
 * @immutable
 * @readonly
 */
readonly class Menu extends Base
{
    use WithHelpers;
    use WithStrictHelpers;
    use WithInheritance;

    /**
     * Создаёт новый экземпляр компонента Menu.
     *
     * @param  array<array{label: string, url?: string, action?: string, icon?: string, active?: bool, children?:
     *     array, danger?: bool, disabled?: bool}>  $items  Элементы меню
     * @param  string  $orientation  Ориентация (horizontal|vertical)
     * @param  string  $variant  Вариант оформления (navbar|sidebar|dropdown|context)
     * @param  string  $position  Позиция (top|left|right|bottom|floating)
     * @param  string  $trigger  Триггер показа (always|hover|click|context)
     * @param  string|null  $brand  Текст бренда
     * @param  bool  $collapsible  Сворачиваемое ли меню
     * @param  string|null  $searchPlaceholder  Плейсхолдер поиска
     * @param  int  $maxNestingLevel  Максимальный уровень вложенности (0 - без ограничений)
     * @param  array<string>  $classes  Дополнительные CSS классы
     * @param  array<string, string|int|bool|null>  $attributes  Дополнительные HTML атрибуты
     */
    public function __construct(
        public array $items = [],
        public string $orientation = 'horizontal',
        public string $variant = 'navbar',
        public string $position = 'top',
        public string $trigger = 'always',
        public ?string $brand = null,
        public bool $collapsible = false,
        public ?string $searchPlaceholder = null,
        public int $maxNestingLevel = 0,
        public array $classes = [],
        public array $attributes = [],
    ) {
        $this->prepare();
    }

    /**
     * Подготовка компонента к рендерингу.
     *
     * @return void
     * @throws InvalidArgumentException Если параметры невалидны
     */
    protected function prepare(): void
    {
        // Только базовые проверки значений

        if (!$this->isValidOrientation($this->orientation)) {
            throw new InvalidArgumentException("Неподдерживаемая ориентация: $this->orientation");
        }

        if (!$this->isValidVariant($this->variant)) {
            throw new InvalidArgumentException("Неподдерживаемый вариант: $this->variant");
        }

        if (!$this->isValidPosition($this->position)) {
            throw new InvalidArgumentException("Неподдерживаемая позиция: $this->position");
        }

        if (!$this->isValidTrigger($this->trigger)) {
            throw new InvalidArgumentException("Неподдерживаемый триггер: $this->trigger");
        }

        // Проверка вложенности (если ограничение задано)
        if ($this->maxNestingLevel > 0 && !$this->validateNesting($this->items)) {
            throw new InvalidArgumentException(
                "Превышен максимальный уровень вложенности: $this->maxNestingLevel",
            );
        }
    }

    /**
     * Проверяет, является ли ориентация допустимой.
     */
    public function isValidOrientation(string $orientation): bool
    {
        return in_array($orientation, ['horizontal', 'vertical'], true);
    }

    /**
     * Проверяет, является ли вариант допустимым.
     */
    public function isValidVariant(string $variant): bool
    {
        return in_array($variant, ['navbar', 'sidebar', 'dropdown', 'context'], true);
    }

    /**
     * Проверяет, является ли позиция допустимой.
     */
    public function isValidPosition(string $position): bool
    {
        return in_array($position, ['top', 'left', 'right', 'bottom', 'floating'], true);
    }

    /**
     * Проверяет, является ли триггер допустимым.
     */
    public function isValidTrigger(string $trigger): bool
    {
        return in_array($trigger, ['always', 'hover', 'click', 'context'], true);
    }

    /**
     * Возвращает массив CSS-классов для меню.
     *
     * @return array<string> Массив CSS-классов
     */
    public function getMenuClasses(): array
    {
        $classes = [
            'wallkit-menu',
            "wallkit-menu--$this->orientation",
            "wallkit-menu--$this->variant",
            "wallkit-menu--position-$this->position",
            "wallkit-menu--trigger-$this->trigger",
        ];

        if ($this->collapsible) {
            $classes[] = 'wallkit-menu--collapsible';
        }

        if ($this->brand !== null) {
            $classes[] = 'wallkit-menu--has-brand';
        }

        if ($this->searchPlaceholder !== null) {
            $classes[] = 'wallkit-menu--has-search';
        }

        if ($this->hasNestedItems()) {
            $classes[] = 'wallkit-menu--has-nested';
        }

        return array_merge($classes, $this->classes);
    }

    /**
     * Возвращает все HTML-атрибуты для меню.
     *
     * @return array<string, string|int|bool|null> Ассоциативный массив атрибутов
     */
    public function getMenuAttributes(): array
    {
        $attrs = array_merge([
            'class' => $this->classList($this->getMenuClasses()),
            'role' => $this->getMenuRole(),
            'aria-orientation' => $this->orientation,
            'data-variant' => $this->variant,
            'data-trigger' => $this->trigger,
            'data-position' => $this->position,
        ], $this->attributes);

        if ($this->variant === 'dropdown' || $this->variant === 'context') {
            $attrs['tabindex'] = '-1';
        }

        return array_filter($attrs, fn($value) => $value !== null);
    }

    /**
     * Возвращает ARIA роль для меню.
     */
    private function getMenuRole(): string
    {
        return match ($this->variant) {
            'navbar', 'sidebar' => 'navigation',
            default => 'menu',
        };
    }

    /**
     * Рекурсивно проверяет уровень вложенности элементов.
     *
     * @param  array  $items  Элементы для проверки
     * @param  int  $currentLevel  Текущий уровень
     *
     * @return bool true если вложенность допустима
     */
    private function validateNesting(array $items, int $currentLevel = 1): bool
    {
        if ($this->maxNestingLevel > 0 && $currentLevel > $this->maxNestingLevel) {
            return false;
        }

        foreach ($items as $item) {
            if (isset($item['children'])) {
                if (!$this->validateNesting($item['children'], $currentLevel + 1)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Возвращает подготовленные элементы для шаблона.
     *
     * @return array<array{label: string, url?: string, action?: string, icon?: string, active: bool, children?: array,
     *     danger?: bool, disabled?: bool}>
     */
    public function getPreparedItems(): array
    {
        return array_map(function ($item) {
            return [
                'label' => $item['label'] ?? '',
                'url' => $item['url'] ?? null,
                'action' => $item['action'] ?? null,
                'icon' => $item['icon'] ?? null,
                'active' => $item['active'] ?? false,
                'children' => $item['children'] ?? null,
                'danger' => $item['danger'] ?? false,
                'disabled' => $item['disabled'] ?? false,
            ];
        }, $this->items);
    }

    /**
     * Проверяет, есть ли в меню вложенные элементы.
     *
     * @return bool true если есть вложенные элементы
     */
    public function hasNestedItems(): bool
    {
        foreach ($this->items as $item) {
            if (isset($item['children']) && !empty($item['children'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Возвращает CSS классы для элемента меню.
     *
     * @param  array  $item  Элемент меню
     * @param  int  $level  Уровень вложенности
     *
     * @return array<string> Массив CSS классов
     */
    public function getItemClasses(array $item, int $level = 1): array
    {
        $classes = ['wallkit-menu__item'];

        if ($item['active']) {
            $classes[] = 'wallkit-menu__item--active';
        }
        if ($item['danger']) {
            $classes[] = 'wallkit-menu__item--danger';
        }
        if ($item['disabled']) {
            $classes[] = 'wallkit-menu__item--disabled';
        }
        if (!empty($item['children'])) {
            $classes[] = 'wallkit-menu__item--has-children';
        }
        if ($level > 1) {
            $classes[] = 'wallkit-menu__item--nested';
        }

        return $classes;
    }
}