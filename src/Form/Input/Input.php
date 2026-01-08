<?php

declare(strict_types=1);

namespace OlegV\WallKit\Form\Input;

use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithInheritance;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;

/**
 * Чистый компонент Input - базовое поле ввода без обёртки
 *
 * @example
 * $input = new Input(
 *     name: 'username',
 *     placeholder: 'Введите ваше имя'
 * );
 */
readonly class Input extends Base
{
    use WithHelpers;
    use WithStrictHelpers;
    use WithInheritance;

    /**
     * @param  string  $name  Название поля (атрибут name)
     * @param  string|null  $placeholder  Плейсхолдер
     * @param  string|null  $value  Текущее значение
     * @param  string  $type  Тип поля (text, email, password и т.д.)
     * @param  bool  $required  Обязательное ли поле
     * @param  bool  $disabled  Заблокировано ли поле
     * @param  bool  $readonly  Только для чтения
     * @param  string|null  $id  ID поля (автогенерируется если не указан)
     * @param  array<string>  $classes  Дополнительные CSS классы
     * @param  array<string, string|int|bool|null>  $attributes  Дополнительные HTML атрибуты
     * @param  bool  $autoFocus  Автофокус при загрузке
     * @param  string|null  $pattern  Регулярное выражение для валидации
     * @param  string|null  $min  Минимальное значение (для number/date)
     * @param  string|null  $max  Максимальное значение (для number/date)
     * @param  int|null  $maxLength  Максимальная длина
     * @param  int|null  $minLength  Минимальная длина
     * @param  string|null  $step  Шаг для числовых полей
     * @param  string|null  $autocomplete  Значение атрибута autocomplete
     *                                    (например: "on", "off", "name", "email", "username")
     * @param  bool|null  $spellcheck  Включить/выключить проверку орфографии
     */
    public function __construct(
        public string $name,
        public ?string $placeholder = null,
        public ?string $value = null,
        public string $type = 'text',
        public bool $required = false,
        public bool $disabled = false,
        public bool $readonly = false,
        public ?string $id = null,
        public array $classes = [],
        public array $attributes = [],
        public bool $autoFocus = false,
        public ?string $pattern = null,
        public ?string $min = null,
        public ?string $max = null,
        public ?int $maxLength = null,
        public ?int $minLength = null,
        public ?string $step = null,
        public ?string $autocomplete = null,
        public ?bool $spellcheck = null,
    ) {
        if (!$this->isValidType($this->type)) {
            trigger_error("Неподдерживаемый тип: $this->type", E_USER_WARNING);
        }
        if (!$this->hasString(trim($this->name))) {
            trigger_error('Имя поля Input обязательно и не может состоять только из пробелов', E_USER_WARNING);
        }
    }

    /**
     * Получить CSS классы для input
     * @return array<string>
     */
    public function getInputClasses(): array
    {
        $classes = ['wallkit-input__field'];
        return array_merge($classes, $this->classes);
    }

    /**
     * Получить все атрибуты для input
     * @return array<string, string|int|bool|null>
     */
    public function getInputAttributes(): array
    {
        $attrs = array_merge([
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'class' => $this->classList($this->getInputClasses()),
            'placeholder' => $this->placeholder,
            'value' => $this->value,
            'autocomplete' => $this->autocomplete,
        ], $this->attributes);

        // Булевые атрибуты
        if ($this->required) {
            $attrs['required'] = true;
        }
        if ($this->disabled) {
            $attrs['disabled'] = true;
        }
        if ($this->readonly) {
            $attrs['readonly'] = true;
        }
        if ($this->autoFocus) {
            $attrs['autofocus'] = true;
        }

        // Валидационные атрибуты
        if ($this->hasString($this->pattern)) {
            $attrs['pattern'] = $this->pattern;
        }
        if ($this->min !== null) {
            $attrs['min'] = $this->min;
        }
        if ($this->max !== null) {
            $attrs['max'] = $this->max;
        }
        if ($this->maxLength !== null) {
            $attrs['maxlength'] = $this->maxLength;
        }
        if ($this->minLength !== null) {
            $attrs['minlength'] = $this->minLength;
        }
        if ($this->step !== null) {
            $attrs['step'] = $this->step;
        }
        if ($this->spellcheck !== null) {
            $attrs['spellcheck'] = $this->spellcheck ? 'true' : 'false';
        }

        // Удаляем null значения
        return array_filter($attrs, fn($value) => $value !== null);
    }

    /**
     * Валидация типа поля
     */
    public function isValidType(string $type): bool
    {
        $validTypes = [
            'color',
            'date',
            'datetime-local',
            'email',
            'file',
            'hidden',
            'month',
            'number',
            'password',
            'range',
            'search',
            'tel',
            'text',
            'time',
            'url',
            'week',
        ];

        return in_array($type, $validTypes, true);
    }
}