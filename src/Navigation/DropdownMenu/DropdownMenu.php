<?php

declare(strict_types=1);

namespace OlegV\WallKit\Navigation\DropdownMenu;

use InvalidArgumentException;
use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;
use OlegV\WallKit\Navigation\Item\Item;

/**
 * Компонент DropdownMenu - выпадающее меню
 *
 * Открывается по клику или наведению на триггер-элемент.
 * Позиционируется относительно триггера.
 * Автоматически закрывается при клике вне меню или выборе пункта.
 *
 * ## Примеры использования
 *
 * ### Выпадающее меню с кнопкой
 * ```php
 * $dropdown = new DropdownMenu(
 *     items: [
 *         Item::link('Профиль', '/profile', '👤'),
 *         Item::link('Настройки', '/settings', '⚙️'),
 *         Item::divider(),
 *         Item::action('Выйти', 'logout', '🚪', danger: true),
 *     ],
 *     triggerText: 'Меню пользователя',
 *     triggerIcon: '▼',
 * );
 * echo $dropdown;
 * ```
 *
 * ### Выпадающее меню с кастомным триггером
 * ```php
 * $dropdown = new DropdownMenu(
 *     items: [...],
 *     triggerElement: '<button class="custom-btn">Действия</button>',
 *     position: 'right',
 *     trigger: 'hover',
 * );
 * echo $dropdown;
 * ```
 *
 * @package OlegV\WallKit\Navigation\DropdownMenu
 * @readonly
 * @immutable
 * @since 1.0.0
 */
readonly class DropdownMenu extends Base
{
    use WithHelpers;
    use WithStrictHelpers;

    public string $triggerId;

    /**
     * Создаёт новый экземпляр выпадающего меню
     *
     * @param  array<Item>  $items  Элементы меню
     * @param  string  $trigger  Способ открытия (click|hover)
     * @param  string  $position  Позиция относительно триггера (top|bottom|left|right)
     * @param  string|null  $triggerText  Текст на кнопке-триггере
     * @param  string|null  $triggerIcon  Иконка на кнопке-триггере
     * @param  string|null  $triggerElement  HTML триггер-элемент (альтернатива triggerText/Icon)
     * @param  string|null  $triggerId  ID триггер-элемента (для связи с JS)
     * @param  bool  $closeOnClick  Закрывать ли меню при клике на пункт
     * @param  array<string>  $classes  Дополнительные CSS классы
     * @param  array<string, mixed>  $attributes  Дополнительные HTML атрибуты
     */
    public function __construct(
        public array $items = [],
        public string $trigger = 'click',
        public string $position = 'bottom',
        public ?string $triggerText = null,
        public ?string $triggerIcon = null,
        public ?string $triggerElement = null,
        ?string $triggerId = null,
        public bool $closeOnClick = true,
        public array $classes = [],
        public array $attributes = [],
    ) {
        // Генерируем ID если не указан
        if ($triggerId === null) {
            $this->triggerId = 'dropdown-' . uniqid();
        } else {
            $this->triggerId = $triggerId;
        }
    }

    /**
     * Подготовка компонента к рендерингу
     */
    protected function prepare(): void
    {
        // Проверяем что все items - экземпляры Item
        foreach ($this->items as $item) {
            if (!$item instanceof Item) {
                throw new InvalidArgumentException('Все элементы меню должны быть экземплярами Item');
            }
        }

        // Валидация триггера
        if (!in_array($this->trigger, ['click', 'hover'], true)) {
            throw new InvalidArgumentException("Неподдерживаемый триггер: $this->trigger");
        }

        // Валидация позиции
        $validPositions = ['top', 'bottom', 'left', 'right'];
        if (!in_array($this->position, $validPositions, true)) {
            throw new InvalidArgumentException("Неподдерживаемая позиция: $this->position");
        }

        // Проверяем наличие триггера
        if ($this->triggerText === null && $this->triggerElement === null) {
            throw new InvalidArgumentException('DropdownMenu требует triggerText или triggerElement');
        }
    }

    /**
     * Возвращает CSS классы для выпадающего меню
     */
    public function getMenuClasses(): array
    {
        $classes = [
            'wallkit-dropdown-menu',
            'wallkit-dropdown-menu--hidden',
            "wallkit-dropdown-menu--position-$this->position",
            "wallkit-dropdown-menu--trigger-$this->trigger",
        ];

        return array_merge($classes, $this->classes);
    }

    /**
     * Возвращает HTML атрибуты для выпадающего меню
     */
    public function getMenuAttributes(): array
    {
        $attrs = array_merge([
            'class' => $this->classList($this->getMenuClasses()),
            'role' => 'menu',
            'aria-labelledby' => $this->triggerId,
            'aria-hidden' => 'true',
            'data-trigger' => $this->trigger,
            'data-position' => $this->position,
            'data-close-on-click' => $this->closeOnClick ? 'true' : 'false',
            'id' => 'menu-' . $this->triggerId,
        ], $this->attributes);

        return array_filter($attrs, fn ($value) => $value !== null);
    }

    /**
     * Генерирует HTML для триггер-элемента
     */
    public function renderTrigger(): string
    {
        if ($this->triggerElement !== null) {
            return $this->triggerElement;
        }

        $icon = $this->triggerIcon
            ? '<span class="wallkit-dropdown-menu__trigger-icon">'
            . $this->e($this->triggerIcon)
            . '</span>' : '';

        $text = $this->triggerText
            ? '<span class="wallkit-dropdown-menu__trigger-text">'
            . $this->e($this->triggerText)
            . '</span>' : '';

        $triggerClasses = [
            'wallkit-dropdown-menu__trigger',
            'wallkit-dropdown-menu__trigger--' . $this->trigger,
        ];

        return sprintf(
            '<button class="%s" id="%s" aria-haspopup="true" aria-expanded="false">%s%s</button>',
            $this->classList($triggerClasses),
            $this->e($this->triggerId),
            $text,
            $icon,
        );
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
