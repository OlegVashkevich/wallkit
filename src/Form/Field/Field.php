<?php

declare(strict_types=1);

namespace OlegV\WallKit\Form\Field;

use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithInheritance;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;
use OlegV\WallKit\Form\Input\Input;
use OlegV\WallKit\Form\Select\Select;
use OlegV\WallKit\Form\Textarea\Textarea;

/**
 * Компонент Field — обёртка для поля ввода с меткой, подсказкой, ошибкой и переключателем пароля
 *
 * Этот компонент предоставляет структурированную обёртку для полей ввода (Input или Textarea)
 * с поддержкой метки (label), текста помощи (help text), отображения ошибок и опциональным
 * переключателем видимости для паролей.
 *
 * ## Примеры использования
 *
 * ### Поле с меткой и подсказкой
 * ```php
 * $field = new Field(
 *     input: new Input(name: 'username'),
 *     label: 'Имя пользователя',
 *     helpText: 'Введите ваше имя'
 * );
 * echo $field;
 * ```
 *
 * ### Поле с ошибкой
 * ```php
 * $field = new Field(
 *     input: new Input(name: 'email', type: 'email'),
 *     label: 'Email',
 *     error: 'Некорректный адрес почты'
 * );
 * ```
 *
 * ### Поле пароля с переключателем
 * ```php
 * $field = new Field(
 *     input: new Input(name: 'password', type: 'password'),
 *     label: 'Пароль',
 *     withPasswordToggle: true
 * );
 * ```
 *
 * ### Textarea с обёрткой
 * ```php
 * $field = new Field(
 *     input: new Textarea(name: 'bio', placeholder: 'О себе'),
 *     label: 'Биография'
 * );
 * ```
 *
 * @package OlegV\WallKit\Form\Field
 * @author OlegV
 * @since 1.0.0
 * @version 1.0.0
 * @immutable
 * @readonly
 */
readonly class Field extends Base
{
    use WithHelpers;
    use WithStrictHelpers;
    use WithInheritance;

    /**
     * Создаёт новый экземпляр компонента Field.
     *
     * @param  Input|Textarea|Select  $input  Объект Input или Textarea для рендеринга внутри поля
     * @param  string|null  $label  Текст метки поля (отображается над полем ввода)
     * @param  string|null  $helpText  Текст подсказки (отображается под полем ввода)
     * @param  string|null  $error  Сообщение об ошибке (подсвечивает поле и отображает текст)
     * @param  bool  $withPasswordToggle  Показывать ли кнопку переключения видимости пароля (только для
     *     type="password")
     * @param  array<string>  $wrapperClasses  Дополнительные CSS-классы для обёртки поля
     */
    public function __construct(
        public Input|Textarea|Select $input,
        public ?string $label = null,
        public ?string $helpText = null,
        public ?string $error = null,
        public bool $withPasswordToggle = true,
        public array $wrapperClasses = [],
    ) {}

    /**
     * Возвращает массив CSS-классов для обёртки поля.
     *
     * Автоматически добавляются классы для состояний ошибки и отключения,
     * а также пользовательские классы из $wrapperClasses.
     *
     * @return array<string> Массив CSS-классов для элемента обёртки
     */
    public function getWrapperClasses(): array
    {
        $classes = ['wallkit-field'];

        if ($this->hasString($this->error)) {
            $classes[] = 'wallkit-field--error';
        }

        if ($this->input->disabled) {
            $classes[] = 'wallkit-field--disabled';
        }

        return array_merge($classes, $this->wrapperClasses);
    }

    /**
     * Возвращает ID поля ввода для связи с меткой (label for="...").
     *
     * Используется для корректной связи метки с полем через атрибут `for`.
     *
     * @return string|null ID поля ввода или null, если ID не установлен
     */
    public function getLabelId(): ?string
    {
        return $this->input->id;
    }

    /**
     * Определяет, нужно ли показывать переключатель видимости пароля.
     *
     * Переключатель отображается только если:
     * 1. Тип поля — 'password'
     * 2. Параметр $withPasswordToggle установлен в true
     *
     * @return bool true если переключатель должен быть отображён, false в противном случае
     */
    public function shouldShowPasswordToggle(): bool
    {
        return property_exists($this->input, 'type')
            && $this->input->type === 'password'
            && $this->withPasswordToggle;
    }

    /**
     * Возвращает тип поля для CSS классов
     *
     * @return string
     */
    public function getFieldType(): string
    {
        if ($this->input instanceof Textarea) {
            return 'textarea';
        }

        if ($this->input instanceof Select) {
            return 'select';
        }

        // Для Input берем тип из свойства
        return $this->input->type ?? 'text';
    }

    /**
     * Проверяет, является ли поле radio/checkbox
     *
     * @return bool
     */
    public function isCheckable(): bool
    {
        $type = $this->getFieldType();
        return $type === 'radio' || $type === 'checkbox';
    }
}
