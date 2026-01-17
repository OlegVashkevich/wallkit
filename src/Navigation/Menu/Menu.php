<?php

declare(strict_types=1);

namespace OlegV\WallKit\Navigation\Menu;

use InvalidArgumentException;
use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;
use OlegV\WallKit\Navigation\Item\Item;

/**
 * Компонент Menu - основное меню для навигации (navbar/sidebar)
 *
 * Всегда видимое меню для основной навигации сайта или приложения.
 * Поддерживает горизонтальную и вертикальную ориентацию.
 * Не имеет состояний открыто/закрыто - всегда отображается.
 *
 * ## Примеры использования
 *
 * ### Горизонтальное меню (Navbar)
 * ```php
 * $menu = new Menu([
 *     Item::link('Главная', '/', '🏠', active: true),
 *     Item::link('О нас', '/about'),
 *     Item::parent('Услуги', [
 *         Item::link('Разработка', '/services/dev'),
 *         Item::link('Дизайн', '/services/design'),
 *     ], '🎯'),
 * ]);
 * echo $menu;
 * ```
 *
 * ### Вертикальное меню (Sidebar)
 * ```php
 * $menu = new Menu(
 *     items: [...],
 *     orientation: 'vertical',
 *     position: 'left',
 *     collapsible: true,
 *     brand: 'Админ-панель',
 * );
 * echo $menu;
 * ```
 *
 * @package OlegV\WallKit\Navigation\Menu
 * @readonly
 * @immutable
 * @since 1.0.0
 */
readonly class Menu extends Base
{
    use WithHelpers;
    use WithStrictHelpers;

    /**
     * Создаёт новый экземпляр основного меню
     *
     * @param  array<Item>  $items  Элементы меню
     * @param  string  $orientation  Ориентация (horizontal|vertical)
     * @param  string  $position  Позиция (top|left|right|bottom)
     * @param  string|null  $brand  Текст бренда/логотипа
     * @param  string|null  $searchPlaceholder  Плейсхолдер для поиска
     * @param  bool  $collapsible  Можно ли сворачивать меню (актуально для мобильных)
     * @param  array<string>  $classes  Дополнительные CSS классы
     * @param  array<string, string|int|bool|null>  $attributes  Дополнительные HTML атрибуты
     */
    public function __construct(
        public array $items = [],
        public string $orientation = 'horizontal',
        public string $position = 'top',
        public ?string $brand = null,
        public ?string $searchPlaceholder = null,
        public bool $collapsible = false,
        public array $classes = [],
        public array $attributes = [],
    ) {}

    /**
     * Подготовка компонента к рендерингу
     */
    protected function prepare(): void
    {
        // Проверяем что все items - экземпляры Item
        foreach ($this->items as $item) {
            if (!$item instanceof Item) {// @phpstan-ignore instanceof.alwaysTrue
                throw new InvalidArgumentException('Все элементы меню должны быть экземплярами Item');
            }
        }

        // Валидация ориентации
        if (!in_array($this->orientation, ['horizontal', 'vertical'], true)) {
            throw new InvalidArgumentException("Неподдерживаемая ориентация: $this->orientation");
        }

        // Валидация позиции
        $validPositions = ['top', 'left', 'right', 'bottom'];
        if (!in_array($this->position, $validPositions, true)) {
            throw new InvalidArgumentException("Неподдерживаемая позиция: $this->position");
        }

        // Проверяем совместимость ориентации и позиции
        if ($this->orientation === 'horizontal' && in_array($this->position, ['left', 'right'], true)) {
            throw new InvalidArgumentException("Горизонтальное меню не может быть позиционировано слева или справа");
        }

        if ($this->orientation === 'vertical' && in_array($this->position, ['top', 'bottom'], true)) {
            throw new InvalidArgumentException("Вертикальное меню не может быть позиционировано сверху или снизу");
        }
    }

    /**
     * Возвращает CSS классы для меню
     *
     * @return array<string>
     */
    public function getMenuClasses(): array
    {
        $classes = [
            'wallkit-menu',
            "wallkit-menu--$this->orientation",
            "wallkit-menu--position-$this->position",
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
     * Возвращает HTML атрибуты для меню
     *
     * @return array<string, string|int|bool|null> Ассоциативный массив атрибутов
     */
    public function getMenuAttributes(): array
    {
        $attrs = array_merge([
            'class' => $this->classList($this->getMenuClasses()),
            'role' => 'navigation',
            'aria-label' => 'Основная навигация',
            'data-orientation' => $this->orientation,
            'data-position' => $this->position,
            'data-collapsible' => $this->collapsible ? 'true' : 'false',
        ], $this->attributes);

        return array_filter($attrs, fn($value) => $value !== null);
    }

    /**
     * Проверяет, есть ли вложенные элементы в меню
     */
    public function hasNestedItems(): bool
    {
        foreach ($this->items as $item) {
            if ($item->hasChildren()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Рендерит все элементы меню
     */
    public function renderItems(): string
    {
        $html = '';
        foreach ($this->items as $item) {
            $html .= $item->render();
        }
        return $html;
    }
}
