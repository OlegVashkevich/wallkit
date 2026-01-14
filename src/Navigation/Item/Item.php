<?php

declare(strict_types=1);

namespace OlegV\WallKit\Navigation\Item;

use InvalidArgumentException;
use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;

/**
 * Компонент элемента навигации - базовый строительный блок для меню, хлебных крошек, вкладок
 *
 * Самодостаточный компонент, который может содержать дочерние элементы и рекурсивно их рендерить.
 * Определяет стандарт отображения для всех навигационных элементов.
 *
 * ## Примеры использования
 *
 * ### Простая ссылка
 * ```php
 * $item = Item::link('Главная', '/', '🏠', active: true);
 * echo $item;
 * ```
 *
 * ### Действие с иконкой
 * ```php
 * $item = Item::action('Удалить', 'delete', '🗑️', danger: true);
 * echo $item;
 * ```
 *
 * ### Родительский элемент с детьми
 * ```php
 * $item = Item::parent('Продукты', [
 *     Item::link('Каталог', '/products'),
 *     Item::link('Новинки', '/products/new'),
 * ], '📦');
 * echo $item;
 * ```
 *
 * ### Специальные элементы
 * ```php
 * $divider = Item::divider();
 * $header = Item::header('Настройки', '⚙️');
 * echo $divider;
 * echo $header;
 * ```
 *
 * @package OlegV\WallKit\Navigation\Item
 * @readonly
 * @immutable
 * @since 1.0.0
 * @version 1.0.0
 */
readonly class Item extends Base
{
    use WithHelpers;
    use WithStrictHelpers;

    /**
     * Создаёт новый элемент навигации
     *
     * @param  string  $label  Текст элемента
     * @param  string|null  $url  URL для ссылки
     * @param  string|null  $action  Действие/команда
     * @param  string|null  $icon  Иконка (emoji или CSS-класс)
     * @param  bool  $active  Активный ли элемент
     * @param  bool  $danger  Опасное действие (удаление и т.д.)
     * @param  bool  $disabled  Отключен ли элемент
     * @param  string|null  $target  Цель ссылки (_blank, _self, _parent, _top)
     * @param  string|null  $rel  Атрибут rel для ссылки
     * @param  array<string, string>|null  $data  data-атрибуты
     * @param  string|null  $id  Уникальный идентификатор
     * @param  string|null  $badge  Бейдж/метка (число или текст)
     * @param  string|null  $hint  Подсказка/описание
     * @param  string  $type  Тип элемента (link|action|divider|header|custom)
     * @param  array<Item>  $children  Дочерние элементы
     * @param  array<string, mixed>  $meta  Дополнительные метаданные
     */
    public function __construct(
        public string $label,
        public ?string $url = null,
        public ?string $action = null,
        public ?string $icon = null,
        public bool $active = false,
        public bool $danger = false,
        public bool $disabled = false,
        public ?string $target = null,
        public ?string $rel = null,
        public ?array $data = null,
        public ?string $id = null,
        public ?string $badge = null,
        public ?string $hint = null,
        public string $type = 'link',
        public array $children = [],
        public array $meta = [],
    ) {}

    /**
     * Валидация элемента
     */
    protected function prepare(): void
    {
        // Проверяем допустимый тип
        if (!in_array($this->type, ['link', 'action', 'divider', 'header', 'custom'])) {
            throw new InvalidArgumentException("Недопустимый тип элемента: $this->type");
        }

        // Проверяем обязательные поля для типов
        if ($this->type === 'link' && $this->url === null) {
            throw new InvalidArgumentException('Элемент типа link должен иметь url');
        }

        if ($this->type === 'action' && $this->action === null) {
            throw new InvalidArgumentException('Элемент типа action должен иметь action');
        }

        if ($this->type === 'divider' && $this->label !== '') {
            throw new InvalidArgumentException('Элемент типа divider должен иметь пустой label');
        }

        // Проверяем children
        foreach ($this->children as $child) {
            if (!$child instanceof self) {
                throw new InvalidArgumentException(
                    'Все children должны быть экземплярами '.self::class,
                );
            }
        }

        // Нельзя иметь и url/action и children одновременно для обычных типов
        $hasContent = ($this->url !== null) || ($this->action !== null);
        $hasChildren = !empty($this->children);

        if ($hasContent && $hasChildren && !in_array($this->type, ['header', 'custom'])) {
            throw new InvalidArgumentException(
                'Элемент не может одновременно иметь url/action и children',
            );
        }

        // Валидация target
        if ($this->target !== null && !in_array($this->target, ['_blank', '_self', '_parent', '_top'])) {
            throw new InvalidArgumentException("Недопустимое значение target: $this->target");
        }

        // Если есть data, проверяем что это ассоциативный массив строк
        if ($this->data !== null) {
            foreach ($this->data as $key => $value) {
                if (!is_string($key) || !is_string($value)) {
                    throw new InvalidArgumentException(
                        'data-атрибуты должны быть массивом строковых ключ=>значение',
                    );
                }
            }
        }
    }

    /**
     * Имеет ли элемент дочерние элементы
     */
    public function hasChildren(): bool
    {
        return !empty($this->children);
    }

    /**
     * Является ли элемент интерактивным (не divider/header)
     */
    public function isInteractive(): bool
    {
        return !in_array($this->type, ['divider', 'header']);
    }

    /**
     * Получить HTML тег для элемента
     */
    public function getTag(): string
    {
        if ($this->type === 'divider' || $this->type === 'header') {
            return 'div';
        }
        return $this->url ? 'a' : 'button';
    }

    /**
     * Получить HTML атрибуты для элемента
     */
    public function getAttributes(): array
    {
        $attrs = [
            'class' => $this->classList([
                'wallkit-item',
                "wallkit-item--$this->type",
                $this->active ? 'wallkit-item--active' : '',
                $this->danger ? 'wallkit-item--danger' : '',
                $this->disabled ? 'wallkit-item--disabled' : '',
                $this->hasChildren() ? 'wallkit-item--has-children' : '',
            ]),
            'id' => $this->id,
            'title' => $this->hint,
        ];

        if ($this->isInteractive()) {
            $attrs['aria-disabled'] = $this->disabled ? 'true' : null;
        }

        if ($this->url) {
            $attrs['href'] = $this->url;
            $attrs['target'] = $this->target;
            $attrs['rel'] = $this->rel;
        } elseif ($this->action && $this->type === 'action') {
            $attrs['type'] = 'button';
            $attrs['data-action'] = $this->action;
        }

        if ($this->data) {
            foreach ($this->data as $key => $value) {
                $attrs["data-$key"] = $value;
            }
        }

        return array_filter($attrs, fn($value) => $value !== null);
    }

    /**
     * Рекурсивно получает все элементы (для валидации вложенности)
     *
     * @param  int  $currentLevel  Текущий уровень вложенности
     *
     * @return array<array{item: Item, level: int}> Все элементы с их уровнями
     */
    public function getAllItems(int $currentLevel = 1): array
    {
        $items = [['item' => $this, 'level' => $currentLevel]];

        foreach ($this->children as $child) {
            $items = array_merge($items, $child->getAllItems($currentLevel + 1));
        }

        return $items;
    }

    /**
     * Создать элемент-разделитель
     */
    public static function divider(string $id = null): self
    {
        return new self(
            label: '',
            id: $id,
            type: 'divider',
        );
    }

    /**
     * Создать элемент-заголовок группы
     */
    public static function header(string $label, ?string $icon = null, string $id = null): self
    {
        return new self(
            label: $label,
            icon: $icon,
            id: $id,
            type: 'header',
        );
    }

    /**
     * Создать кастомный элемент
     */
    public static function custom(
        string $label,
        array $meta = [],
        ?string $icon = null,
        string $id = null,
        array $children = [],
    ): self {
        return new self(
            label: $label,
            icon: $icon,
            id: $id,
            type: 'custom',
            children: $children,
            meta: array_merge(['type' => 'custom'], $meta),
        );
    }

    /**
     * Создать ссылку
     */
    public static function link(
        string $label,
        string $url,
        ?string $icon = null,
        bool $active = false,
        ?string $target = null,
        ?string $rel = null,
        ?array $data = null,
        string $id = null,
        ?string $badge = null,
        ?string $hint = null,
        array $children = [],
    ): self {
        return new self(
            label: $label,
            url: $url,
            icon: $icon,
            active: $active,
            target: $target,
            rel: $rel,
            data: $data,
            id: $id,
            badge: $badge,
            hint: $hint,
            type: 'link',
            children: $children,
        );
    }

    /**
     * Создать действие
     */
    public static function action(
        string $label,
        string $action,
        ?string $icon = null,
        bool $danger = false,
        bool $disabled = false,
        ?array $data = null,
        string $id = null,
        ?string $badge = null,
        ?string $hint = null,
        array $children = [],
    ): self {
        return new self(
            label: $label,
            action: $action,
            icon: $icon,
            danger: $danger,
            disabled: $disabled,
            data: $data,
            id: $id,
            badge: $badge,
            hint: $hint,
            type: 'action',
            children: $children,
        );
    }

    /**
     * Создать родительский элемент с детьми
     */
    public static function parent(
        string $label,
        array $children,
        ?string $icon = null,
        bool $active = false,
        string $id = null,
        ?string $badge = null,
        ?string $hint = null,
    ): self {
        return new self(
            label: $label,
            icon: $icon,
            active: $active,
            id: $id,
            badge: $badge,
            hint: $hint,
            type: 'custom',
            children: $children,
        );
    }
}