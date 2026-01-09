<?php

declare(strict_types=1);

namespace OlegV\WallKit\Form\Input;

use InvalidArgumentException;
use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithInheritance;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;

/**
 * Компонент Input — базовое поле ввода без обёртки
 *
 * Этот компонент реализует стандартный HTML-инпут с поддержкой всех распространённых типов
 * и атрибутов. Предназначен для использования как в одиночном режиме, так и внутри компонентов
 * Form и Field.
 *
 * ## Примеры использования
 *
 * ### Простой текст
 * ```php
 * $input = new Input(
 * name: 'username',
 * placeholder: 'Введите ваше имя'
 * );
 * echo $input;
 * ```
 *
 * ### Радио-кнопка
 * ```php
 * $radio = new Input(
 * name: 'theme',
 * type: 'radio',
 * value: 'dark',
 * checked: true,
 * id: 'theme-dark'
 * );
 * ```
 *
 * ### Чекбокс
 * ```php
 * $checkbox = new Input(
 * name: 'agree',
 * type: 'checkbox',
 * value: 'yes',
 * checked: true,
 * required: true
 * );
 * ```
 *
 * ### С валидацией
 * ```php
 * $emailInput = new Input(
 * name: 'email',
 * type: 'email',
 * required: true,
 * autocomplete: 'email'
 * );
 * ```
 *
 * @package OlegV\WallKit\Form\Input
 * @author OlegV
 * @since 1.0.0
 * @version 1.0.0
 * @immutable
 * @readonly
 */
readonly class Input extends Base
{
    use WithHelpers;
    use WithStrictHelpers;
    use WithInheritance;

    /**
     * Создаёт новый экземпляр компонента Input.
     *
     * @param  string  $name  Название поля (атрибут name)
     * @param  string|null  $placeholder  Плейсхолдер
     * @param  string|null  $value  Текущее значение
     * @param  string  $type  Тип поля (text, email, password, radio, checkbox и т.д.)
     * @param  bool  $required  Обязательное ли поле
     * @param  bool  $disabled  Заблокировано ли поле
     * @param  bool  $readonly  Только для чтения
     * @param  bool  $checked  Отмечен ли элемент (для radio/checkbox)
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
     * @param  bool|null  $spellcheck  Включить/выключить проверку орфографии
     *
     */
    public function __construct(
            public string $name,
            public ?string $placeholder = null,
            public ?string $value = null,
            public string $type = 'text',
            public bool $required = false,
            public bool $disabled = false,
            public bool $readonly = false,
            public bool $checked = false,
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
    ) {}

    /**
     * Подготовка компонента к рендерингу.
     *
     * Выполняет валидацию параметров компонента перед использованием.
     * Вызывается автоматически при рендеринге.
     *
     * @return void
     *
     * @throws InvalidArgumentException Если тип поля не поддерживается
     * @throws InvalidArgumentException Если имя поля пустое
     *
     * @internal
     */
    protected function prepare(): void
    {
        if ( ! $this->isValidType($this->type)) {
            throw new InvalidArgumentException("Неподдерживаемый тип: $this->type");
        }
        if ( ! $this->hasString(trim($this->name))) {
            throw new InvalidArgumentException(
                    "Имя поля обязательно и не может состоять только из пробелов",
            );
        }
    }

    /**
     * Возвращает массив CSS-классов для поля ввода.
     *
     * @return array<string> Массив CSS-классов
     */
    public function getInputClasses(): array
    {
        $classes = ['wallkit-input__field'];
        return array_merge($classes, $this->classes);
    }

    /**
     * Возвращает все HTML-атрибуты для поля ввода.
     *
     * Собирает атрибуты из всех свойств компонента и дополнительных атрибутов.
     *
     * @return array<string, string|int|bool|null> Ассоциативный массив атрибутов
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
        if ($this->checked) { //для radio/checkbox
            $attrs['checked'] = true;
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
     * Проверяет, является ли тип поля допустимым.
     *
     * @param  string  $type  Тип поля для проверки
     *
     * @return bool true если тип поддерживается, false в противном случае
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
                'radio',
                'checkbox',
        ];

        return in_array($type, $validTypes, true);
    }
}