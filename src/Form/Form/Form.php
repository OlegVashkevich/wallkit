<?php

declare(strict_types=1);

namespace OlegV\WallKit\Form\Form;

use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithInheritance;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;

/**
 * Компонент Form — контейнер для полей ввода с валидацией и обработкой отправки
 *
 * Этот компонент предоставляет структурированную HTML-форму с поддержкой:
 * - CSRF-токена
 * - Группировки полей
 * - Валидации на стороне сервера
 * - JS-обработки отправки (опционально)
 *
 * ## Примеры использования
 *
 * ### Простая форма
 * ```php
 * $form = new Form(
 *     fields: [
 *         new Input(name: 'username', label: 'Имя пользователя'),
 *         new Input(name: 'email', type: 'email', label: 'Email'),
 *         new Button('Отправить', type: 'submit')
 *     ],
 *     action: '/submit',
 *     method: 'POST'
 * );
 * echo $form;
 * ```
 *
 * ### Форма с CSRF и валидацией
 * ```php
 * $form = new Form(
 *     fields: [
 *         new Field(
 *             input: new Input(name: 'password', type: 'password'),
 *             label: 'Пароль'
 *         ),
 *         new Button('Войти', type: 'submit')
 *     ],
 *     action: '/login',
 *     method: 'POST',
 *     csrfToken: 'abc123...'
 * );
 * ```
 *
 * @package OlegV\WallKit\Form\Form
 * @author OlegV
 * @since 1.0.0
 * @version 1.0.0
 * @immutable
 * @readonly
 */
readonly class Form extends Base
{
    use WithHelpers;
    use WithStrictHelpers;
    use WithInheritance;

    /**
     * Создаёт новый экземпляр компонента Form.
     *
     * @param  array<string>  $fields  Массив полей формы - любой Stringable объект
     * @param  string  $action  URL обработки формы
     * @param  string  $method  HTTP метод (GET, POST)
     * @param  string|null  $csrfToken  CSRF-токен для защиты
     * @param  string|null  $id  ID формы
     * @param  string|null  $name  Имя формы
     * @param  bool  $novalidate  Отключить браузерную валидацию
     * @param  bool  $autoComplete  Включить автозаполнение
     * @param  string|null  $enctype  Тип кодировки (multipart/form-data для файлов)
     * @param  string|null  $target  Цель отправки (_blank, _self)
     * @param  array<string>  $classes  Дополнительные CSS-классы
     * @param  array<string, string|int|bool|null>  $attributes  Дополнительные HTML-атрибуты
     */
    public function __construct(
        public array $fields,
        public string $action = '',
        public string $method = 'POST',
        public ?string $csrfToken = null,
        public ?string $id = null,
        public ?string $name = null,
        public bool $novalidate = false,
        public bool $autoComplete = true,
        public ?string $enctype = null,
        public ?string $target = null,
        public array $classes = [],
        public array $attributes = [],
    ) {}

    /**
     * Возвращает массив CSS-классов для формы.
     *
     * @return array<string> Массив CSS-классов
     */
    public function getFormClasses(): array
    {
        $classes = ['wallkit-form'];
        return array_merge($classes, $this->classes);
    }

    /**
     * Возвращает все HTML-атрибуты для формы.
     *
     * @return array<string, string|int|bool|null> Ассоциативный массив атрибутов
     */
    public function getFormAttributes(): array
    {
        $attrs = array_merge([
            'id' => $this->id,
            'name' => $this->name,
            'action' => $this->action,
            'method' => strtoupper($this->method),
            'class' => $this->classList($this->getFormClasses()),
            'target' => $this->target,
            'enctype' => $this->enctype,
        ], $this->attributes);

        // Булевые атрибуты
        if ($this->novalidate) {
            $attrs['novalidate'] = true;
        }

        if (!$this->autoComplete) {
            $attrs['autocomplete'] = 'off';
        }

        // Удаляем null значения
        return array_filter($attrs, fn($value) => $value !== null);
    }
}