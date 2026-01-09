<?php

declare(strict_types=1);

namespace OlegV\WallKit\Form\Select;

use InvalidArgumentException;
use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithInheritance;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;

/**
 * Компонент Select — выпадающий список (selectbox)
 *
 * Этот компонент реализует стандартный HTML-элемент <select> с поддержкой
 * одиночного и множественного выбора, группировки опций и валидации.
 *
 * ## Примеры использования
 *
 * ### Простой список
 * ```php
 * $select = new Select(
 *     name: 'country',
 *     options: [
 *         'ru' => 'Россия',
 *         'us' => 'США',
 *         'de' => 'Германия'
 *     ],
 *     selected: 'ru'
 * );
 * echo $select;
 * ```
 *
 * ### Множественный выбор
 * ```php
 * $select = new Select(
 *     name: 'skills[]',
 *     options: $skills,
 *     selected: ['php', 'js'],
 *     multiple: true
 * );
 * ```
 *
 * ### С группами опций
 * ```php
 * $select = new Select(
 *     name: 'car',
 *     options: [
 *         'Немецкие' => [
 *             'bmw' => 'BMW',
 *             'audi' => 'Audi'
 *         ],
 *         'Японские' => [
 *             'toyota' => 'Toyota',
 *             'honda' => 'Honda'
 *         ]
 *     ]
 * );
 * ```
 *
 * @package OlegV\WallKit\Form\Select
 * @author OlegV
 * @since 1.0.0
 * @version 1.0.0
 * @immutable
 * @readonly
 */
readonly class Select extends Base
{
    use WithHelpers;
    use WithStrictHelpers;
    use WithInheritance;

    /**
     * @param  string  $name  Название поля (атрибут name)
     * @param  array<string|int, string|array<string|int, string>>  $options  Опции списка
     * @param  string|int|array<string|int>|null  $selected  Выбранное значение
     * @param  bool  $multiple  Множественный выбор
     * @param  bool  $required  Обязательное поле
     * @param  bool  $disabled  Отключенное состояние
     * @param  string|null  $id  ID поля
     * @param  array<string>  $classes  Дополнительные CSS классы
     * @param  array<string, string|int|bool|null>  $attributes  Дополнительные атрибуты
     * @param  string|null  $placeholder  Плейсхолдер (первая пустая опция)
     * @param  int|null  $size  Количество видимых строк (для multiple)
     * @param  bool  $autoFocus  Автофокус при загрузке
     */
    public function __construct(
        public string $name,
        public array $options = [],
        public string|int|array|null $selected = null,
        public bool $multiple = false,
        public bool $required = false,
        public bool $disabled = false,
        public ?string $id = null,
        public array $classes = [],
        public array $attributes = [],
        public ?string $placeholder = null,
        public ?int $size = null,
        public bool $autoFocus = false,
    ) {}

    protected function prepare(): void
    {
        if ($this->name === '') {
            throw new InvalidArgumentException('Имя поля обязательно');
        }

        if ($this->multiple && !str_ends_with($this->name, '[]')) {
            throw new InvalidArgumentException(
                'Для множественного выбора имя должно оканчиваться на "[]"',
            );
        }
    }

    /**
     * Возвращает массив CSS-классов для элемента select.
     *
     * @return array<string> Массив CSS-классов
     */
    public function getSelectClasses(): array
    {
        $classes = ['wallkit-select__field'];
        if ($this->multiple) {
            $classes[] = 'wallkit-select__field--multiple';
        }
        return array_merge($classes, $this->classes);
    }

    /**
     * Возвращает все HTML-атрибуты для элемента select.
     *
     * @return array<string, string|int|bool|null> Ассоциативный массив атрибутов
     */
    public function getSelectAttributes(): array
    {
        $attrs = array_merge([
            'id' => $this->id,
            'name' => $this->name,
            'class' => $this->classList($this->getSelectClasses()),
        ], $this->attributes);

        if ($this->multiple) {
            $attrs['multiple'] = true;
        }

        if ($this->required) {
            $attrs['required'] = true;
        }

        if ($this->disabled) {
            $attrs['disabled'] = true;
        }

        if ($this->autoFocus) {
            $attrs['autofocus'] = true;
        }

        if ($this->size !== null) {
            $attrs['size'] = $this->size;
        }

        return array_filter($attrs, fn($value) => $value !== null);
    }

    /**
     * Проверяет, выбрана ли опция.
     *
     * @param  string|int  $value  Значение опции
     */
    public function isOptionSelected(string|int $value): bool
    {
        if ($this->selected === null) {
            return false;
        }

        if (is_array($this->selected)) {
            if ($this->multiple) {
                return in_array($value, $this->selected, true);
            }
            // тут конечно можно взять первый элемент массива, но мне кажется это не правильно
            return false;
        }

        return (string)$this->selected === (string)$value;
    }

    /**
     * Возвращает нормализованные опции в плоском виде для итерации.
     *
     * @return array<array{value: string|int, label: string, group: string|null}>
     */
    public function getNormalizedOptions(): array
    {
        $normalized = [];

        foreach ($this->options as $key => $value) {
            if (is_array($value)) {
                // Это группа опций
                foreach ($value as $subKey => $subValue) {
                    $normalized[] = [
                        'value' => $subKey,
                        'label' => $subValue,
                        'group' => (string)$key,
                    ];
                }
            } else {
                // Обычная опция
                $normalized[] = [
                    'value' => $key,
                    'label' => $value,
                    'group' => null,
                ];
            }
        }

        return $normalized;
    }
}