<?php

declare(strict_types=1);

namespace OlegV\WallKit\Form\Checkbox;

use InvalidArgumentException;
use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithInheritance;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;

/**
 * Компонент Checkbox — флажок (чекбокс)
 *
 * Компонент реализует стандартный HTML-чекбокс с поддержкой одиночного выбора
 * и группировки через общее имя. Поддерживает все стандартные атрибуты checkbox.
 *
 * ## Примеры использования
 *
 * ### Одиночный чекбокс
 * ```php
 * $checkbox = new Checkbox(
 *     name: 'accept_terms',
 *     label: 'Принимаю условия соглашения'
 * );
 * echo $checkbox;
 * ```
 *
 * ### Группа чекбоксов
 * ```php
 * $hobbies = [
 *     new Checkbox(name: 'hobbies', value: 'reading', label: 'Чтение'),
 *     new Checkbox(name: 'hobbies', value: 'sports', label: 'Спорт'),
 *     new Checkbox(name: 'hobbies', value: 'music', label: 'Музыка'),
 * ];
 * ```
 *
 * ### Предвыбранный чекбокс
 * ```php
 * $newsletter = new Checkbox(
 *     name: 'newsletter',
 *     label: 'Подписаться на рассылку',
 *     checked: true
 * );
 * ```
 *
 * ### Использование с обёрткой Field
 * ```php
 * use OlegV\WallKit\Form\Field\Field;
 * $field = new Field(
 *     input: new Checkbox(name: 'accept', label: 'Согласен'),
 *     helpText: 'Необходимо принять условия'
 * );
 * ```
 *
 * @package OlegV\WallKit\Form\Checkbox
 * @author OlegV
 * @since 1.0.0
 * @version 1.0.0
 * @immutable
 * @readonly
 */
readonly class Checkbox extends Base
{
    use WithHelpers;
    use WithStrictHelpers;
    use WithInheritance;

    /**
     * Создаёт новый экземпляр компонента Checkbox.
     *
     * @param  string  $name  Название поля (атрибут name)
     * @param  string|null  $value  Значение чекбокса (по умолчанию 'on')
     * @param  string|null  $label  Текст подписи к чекбоксу
     * @param  bool  $checked  Выбран ли чекбокс изначально
     * @param  bool  $required  Обязательный ли выбор
     * @param  bool  $disabled  Заблокирован ли чекбокс
     * @param  string|null  $id  ID поля (автогенерируется если не указан)
     * @param  array<string>  $classes  Дополнительные CSS классы
     * @param  array<string, string|int|bool|null>  $attributes  Дополнительные HTML атрибуты
     *
     * @throws InvalidArgumentException Если имя поля пустое
     */
    public function __construct(
            public string $name,
            public ?string $value = 'on',
            public ?string $label = null,
            public bool $checked = false,
            public bool $required = false,
            public bool $disabled = false,
            public ?string $id = null,
            public array $classes = [],
            public array $attributes = [],
    ) {}

    /**
     * Подготовка компонента к рендерингу.
     *
     * Выполняет валидацию параметров компонента перед использованием.
     *
     * @return void
     *
     * @throws InvalidArgumentException Если имя поля пустое
     *
     * @internal
     */
    protected function prepare(): void
    {
        if ( ! $this->hasString(trim($this->name))) {
            throw new InvalidArgumentException(
                    "Имя поля обязательно и не может состоять только из пробелов",
            );
        }
    }

    /**
     * Возвращает массив CSS-классов для контейнера чекбокса.
     *
     * @return array<string> Массив CSS-классов
     */
    public function getContainerClasses(): array
    {
        $classes = ['wallkit-checkbox'];

        if ($this->disabled) {
            $classes[] = 'wallkit-checkbox--disabled';
        }

        return array_merge($classes, $this->classes);
    }

    /**
     * Возвращает массив CSS-классов для самого чекбокса.
     *
     * @return array<string> Массив CSS-классов
     */
    public function getInputClasses(): array
    {
        return ['wallkit-checkbox__input'];
    }

    /**
     * Возвращает все HTML-атрибуты для элемента input.
     *
     * @return array<string, string|int|bool|null> Ассоциативный массив атрибутов
     */
    public function getInputAttributes(): array
    {
        $attrs = array_merge([
                'id' => $this->id,
                'name' => $this->name,
                'value' => $this->value,
                'type' => 'checkbox',
                'class' => $this->classList($this->getInputClasses()),
        ], $this->attributes);

        // Булевые атрибуты
        if ($this->checked) {
            $attrs['checked'] = true;
        }
        if ($this->required) {
            $attrs['required'] = true;
        }
        if ($this->disabled) {
            $attrs['disabled'] = true;
        }

        // Удаляем null значения
        return array_filter($attrs, fn($value) => $value !== null);
    }

    /**
     * Возвращает ID для элемента label.
     *
     * @return string
     */
    public function getLabelFor(): string
    {
        return $this->id ?: $this->generateId();
    }

    /**
     * Генерирует уникальный ID для чекбокса.
     *
     * @return string
     */
    private function generateId(): string
    {
        $base = 'checkbox-'.preg_replace('/[^a-z0-9]/i', '-', $this->name);
        if ($this->value && $this->value !== 'on') {
            $base .= '-'.$this->value;
        }
        return $base;
    }
}