<?php

declare(strict_types=1);

namespace OlegV\WallKit\Form\Textarea;

use InvalidArgumentException;
use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithInheritance;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;

/**
 * Компонент Textarea — многострочное текстовое поле
 *
 * Этот компонент реализует HTML-элемент `<textarea>` с поддержкой всех стандартных атрибутов
 * и дополнительных возможностей для управления размером и поведением текстовой области.
 * Предназначен для ввода длинного текста: комментариев, описаний, сообщений и т.д.
 *
 * ## Примеры использования
 *
 * ### Базовая текстовая область
 * ```php
 * $textarea = new Textarea(
 *     name: 'comment',
 *     placeholder: 'Введите ваш комментарий'
 * );
 * echo $textarea;
 * ```
 *
 * ### Текстовая область с ограничением длины
 * ```php
 * $textarea = new Textarea(
 *     name: 'bio',
 *     placeholder: 'Расскажите о себе',
 *     rows: 5,
 *     maxLength: 500,
 *     helpText: 'Максимум 500 символов'
 * );
 * ```
 *
 * ### Текстовая область с автофокусом
 * ```php
 * $textarea = new Textarea(
 *     name: 'message',
 *     placeholder: 'Напишите сообщение...',
 *     autoFocus: true,
 *     rows: 3
 * );
 * ```
 *
 * @package OlegV\WallKit\Form\Textarea
 * @author OlegV
 * @since 1.0.0
 * @version 1.0.0
 * @immutable
 * @readonly
 */
readonly class Textarea extends Base
{
    use WithHelpers;
    use WithStrictHelpers;
    use WithInheritance;

    /**
     * Создаёт новый экземпляр компонента Textarea.
     *
     * @param  string  $name  Название поля (атрибут name)
     * @param  string|null  $placeholder  Текст-подсказка в поле
     * @param  string|null  $value  Текущее значение текстовой области
     * @param  int  $rows  Количество видимых строк (высота)
     * @param  int|null  $maxLength  Максимальное количество символов
     * @param  bool  $required  Обязательное ли поле для заполнения
     * @param  bool  $disabled  Отключено ли поле
     * @param  bool  $readonly  Только для чтения
     * @param  string|null  $id  Уникальный идентификатор поля
     * @param  array<string>  $classes  Дополнительные CSS-классы
     * @param  array<string, string|int|bool|null>  $attributes  Дополнительные HTML-атрибуты
     * @param  bool  $autoFocus  Автоматический фокус при загрузке
     * @param  string|null  $autocomplete  Значение атрибута автозаполнения
     * @param  bool|null  $spellcheck  Включить/выключить проверку орфографии
     *
     * @throws InvalidArgumentException Если имя поля пустое
     */
    public function __construct(
        public string $name,
        public ?string $placeholder = null,
        public ?string $value = null,
        public int $rows = 4,
        public ?int $maxLength = null,
        public bool $required = false,
        public bool $disabled = false,
        public bool $readonly = false,
        public ?string $id = null,
        public array $classes = [],
        public array $attributes = [],
        public bool $autoFocus = false,
        public ?string $autocomplete = null,
        public ?bool $spellcheck = null,
    ) {}

    /**
     * Подготовка компонента к рендерингу.
     *
     * Выполняет валидацию параметров перед использованием компонента.
     * Вызывается автоматически при рендеринге.
     *
     * @return void
     *
     * @throws InvalidArgumentException Если имя поля пустое или состоит только из пробелов
     *
     * @internal
     */
    protected function prepare(): void
    {
        if (!$this->hasString(trim($this->name))) {
            throw new InvalidArgumentException("Имя поля обязательно");
        }
    }

    /**
     * Возвращает массив CSS-классов для текстовой области.
     *
     * @return array<string> Массив CSS-классов
     */
    public function getTextareaClasses(): array
    {
        $classes = ['wallkit-textarea__field'];
        return array_merge($classes, $this->classes);
    }

    /**
     * Возвращает все HTML-атрибуты для текстовой области.
     *
     * Собирает атрибуты из свойств компонента и дополнительных атрибутов.
     *
     * @return array<string, string|int|bool|null> Ассоциативный массив атрибутов
     */
    public function getTextareaAttributes(): array
    {
        $attrs = array_merge([
            'id' => $this->id,
            'name' => $this->name,
            'class' => $this->classList($this->getTextareaClasses()),
            'placeholder' => $this->placeholder,
            'rows' => $this->rows,
            'autocomplete' => $this->autocomplete,
        ], $this->attributes);

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
        if ($this->maxLength) {
            $attrs['maxlength'] = $this->maxLength;
        }
        if ($this->spellcheck !== null) {
            $attrs['spellcheck'] = $this->spellcheck ? 'true' : 'false';
        }

        return array_filter($attrs, fn($v) => $v !== null);
    }
}