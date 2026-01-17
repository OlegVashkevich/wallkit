<?php

declare(strict_types=1);

namespace OlegV\WallKit\Navigation\ContextMenu;

use InvalidArgumentException;
use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;
use OlegV\WallKit\Navigation\Item\Item;

/**
 * Компонент ContextMenu - контекстное меню (правый клик)
 *
 * Открывается по правому клику (contextmenu) на указанном элементе.
 * Позиционируется относительно курсора мыши.
 * Блокирует стандартное контекстное меню браузера.
 * Автоматически закрывается при клике вне меню или выборе пункта.
 *
 * ## Примеры использования
 *
 * ### Контекстное меню для текстового поля
 * ```php
 * $contextMenu = new ContextMenu(
 *     items: [
 *         Item::action('Вырезать', 'cut', '✂️'),
 *         Item::action('Копировать', 'copy', '📋'),
 *         Item::action('Вставить', 'paste', '📝'),
 *         Item::divider(),
 *         Item::action('Выделить всё', 'selectAll', '☑️'),
 *     ],
 *     target: '.editable-text',
 *     preventDefault: true,
 * );
 * echo $contextMenu;
 * ```
 *
 * ### Контекстное меню для таблицы
 * ```php
 * $contextMenu = new ContextMenu(
 *     items: [
 *         Item::action('Добавить строку', 'addRow', '➕'),
 *         Item::action('Удалить строку', 'deleteRow', '🗑️', danger: true),
 *         Item::divider(),
 *         Item::action('Сортировать', 'sort', '⇅'),
 *     ],
 *     target: '.data-table tr',
 *     menuId: 'table-context-menu',
 * );
 * echo $contextMenu;
 * ```
 *
 * @package OlegV\WallKit\Navigation\ContextMenu
 * @readonly
 * @immutable
 * @since 1.0.0
 */
readonly class ContextMenu extends Base
{
    use WithHelpers;
    use WithStrictHelpers;

    public string $menuId;

    /**
     * Создаёт новый экземпляр контекстного меню
     *
     * @param  array<Item>  $items  Элементы меню
     * @param  string  $target  CSS-селектор элемента(ов), на котором работает меню
     * @param  bool  $preventDefault  Блокировать стандартное контекстное меню браузера
     * @param  string|null  $menuId  Уникальный ID меню (для связи с JS)
     * @param  array<string>  $classes  Дополнительные CSS классы
     * @param  array<string, string|int|bool|null>  $attributes  Дополнительные HTML атрибуты
     */
    public function __construct(
        public array $items = [],
        public string $target = 'body',
        public bool $preventDefault = true,
        ?string $menuId = null,
        public array $classes = [],
        public array $attributes = [],
    ) {
        // Генерируем ID если не указан
        if ($menuId === null) {
            $this->menuId = 'context-menu-'.uniqid();
        } else {
            $this->menuId = $menuId;
        }
    }

    /**
     * Подготовка компонента к рендерингу
     */
    protected function prepare(): void
    {
        // Проверяем что все items - экземпляры Item
        foreach ($this->items as $item) {
            if (!$item instanceof Item) { //@phpstan-ignore instanceof.alwaysTrue
                throw new InvalidArgumentException('Все элементы меню должны быть экземплярами Item');
            }
        }

        // Проверяем target
        if (!$this->hasString($this->target)) {
            throw new InvalidArgumentException('Параметр target обязателен для ContextMenu');
        }

        // Проверяем что target - валидный CSS селектор
        $matchResult = preg_match('/^[a-zA-Z0-9\s.,#\[\]:*^$=+~>_-]+$/', $this->target);
        if ($matchResult === 0 || $matchResult === false) {
            throw new InvalidArgumentException("Некорректный CSS селектор: $this->target");
        }
    }

    /**
     * Возвращает CSS классы для контекстного меню
     *
     * @return array<string>
     */
    public function getMenuClasses(): array
    {
        $classes = [
            'wallkit-context-menu',
            'wallkit-context-menu--hidden',
        ];

        return array_merge($classes, $this->classes);
    }

    /**
     * Возвращает HTML атрибуты для контекстного меню
     *
     * @return array<string, string|int|bool|null> Ассоциативный массив атрибутов
     */
    public function getMenuAttributes(): array
    {
        $attrs = array_merge([
            'class' => $this->classList($this->getMenuClasses()),
            'role' => 'menu',
            'aria-hidden' => 'true',
            'data-target' => $this->target,
            'data-prevent-default' => $this->preventDefault ? 'true' : 'false',
            'id' => $this->menuId,
        ], $this->attributes);

        return array_filter($attrs, fn($value) => $value !== null);
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
