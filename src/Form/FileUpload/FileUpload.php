<?php

declare(strict_types=1);

namespace OlegV\WallKit\Form\FileUpload;

use InvalidArgumentException;
use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;

/**
 * Компонент FileUpload — поле для загрузки одного или нескольких файлов
 *
 * Реализует стандартный HTML5 `<input type="file">` с расширенной валидацией
 * на стороне сервера через атрибуты HTML и PHP-валидацию.
 *
 * ## Примеры использования
 *
 * ### Простая загрузка одного файла
 * ```php
 * $upload = new FileUpload(
 *     name: 'avatar',
 *     label: 'Загрузите аватар',
 *     accept: 'image/*'
 * );
 * echo $upload;
 * ```
 *
 * ### Загрузка нескольких файлов с ограничениями
 * ```php
 * $upload = new FileUpload(
 *     name: 'attachments',
 *     label: 'Прикрепите документы',
 *     multiple: true,
 *     accept: '.pdf,.doc,.docx',
 *     maxFiles: 5,
 *     maxSize: 10 * 1024 * 1024 // 10MB
 * );
 * ```
 *
 * ### Загрузка изображений с ограничениями
 * ```php
 * $upload = new FileUpload(
 *     name: 'gallery',
 *     label: 'Галерея изображений',
 *     multiple: true,
 *     accept: 'image/*',
 *     maxSize: 5 * 1024 * 1024,
 *     maxWidth: 1920,
 *     maxHeight: 1080
 * );
 * ```
 *
 * @package OlegV\WallKit\Form\FileUpload
 * @author OlegV
 * @since 1.0.0
 * @version 1.0.0
 * @immutable
 * @readonly
 */
readonly class FileUpload extends Base
{
    use WithHelpers;
    use WithStrictHelpers;

    /**
     * Создаёт новый экземпляр компонента FileUpload.
     *
     * @param  string  $name  Название поля (атрибут name)
     * @param  string  $label  Подпись поля
     * @param  string|null  $placeholder  Текст в пустом поле
     * @param  bool  $required  Обязательное ли поле
     * @param  bool  $disabled  Отключено ли поле
     * @param  bool  $multiple  Разрешён ли множественный выбор
     * @param  string|null  $accept  Разрешённые типы файлов (MIME или расширения)
     * @param  int|null  $maxSize  Максимальный размер файла в байтах
     * @param  int|null  $maxFiles  Максимальное количество файлов (при multiple)
     * @param  int|null  $maxWidth  Максимальная ширина изображения (пиксели)
     * @param  int|null  $maxHeight  Максимальная высота изображения (пиксели)
     * @param  string|null  $id  HTML ID поля
     * @param  array<string>  $classes  Дополнительные CSS-классы
     * @param  array<string, string|int|bool|null>  $attributes  Дополнительные HTML-атрибуты
     * @param  string|null  $helpText  Подсказка под полем
     * @param  string|null  $error  Сообщение об ошибке
     */
    public function __construct(
        public string $name,
        public string $label,
        public ?string $placeholder = null,
        public bool $required = false,
        public bool $disabled = false,
        public bool $multiple = false,
        public ?string $accept = null,
        public ?int $maxSize = null,
        public ?int $maxFiles = null,
        public ?int $maxWidth = null,
        public ?int $maxHeight = null,
        public ?string $id = null,
        public array $classes = [],
        public array $attributes = [],
        public ?string $helpText = null,
        public ?string $error = null,
    ) {}

    /**
     * Подготовка компонента к рендерингу.
     *
     * Выполняет валидацию параметров перед использованием.
     *
     * @return void
     *
     * @throws InvalidArgumentException Если параметры невалидны
     */
    protected function prepare(): void
    {
        if (!$this->hasString(trim($this->name))) {
            throw new InvalidArgumentException('Имя поля обязательно');
        }

        if (!$this->hasString(trim($this->label))) {
            throw new InvalidArgumentException('Подпись поля обязательна');
        }

        if ($this->maxSize !== null && $this->maxSize <= 0) {
            throw new InvalidArgumentException('Максимальный размер должен быть положительным числом');
        }

        if ($this->maxFiles !== null && $this->maxFiles <= 0) {
            throw new InvalidArgumentException('Максимальное количество файлов должно быть положительным числом');
        }

        if ($this->maxWidth !== null && $this->maxWidth <= 0) {
            throw new InvalidArgumentException('Максимальная ширина должна быть положительным числом');
        }

        if ($this->maxHeight !== null && $this->maxHeight <= 0) {
            throw new InvalidArgumentException('Максимальная высота должна быть положительным числом');
        }
    }

    public function getId(): string
    {
        // Генерация ID, если не передан
        if ($this->id === null) {
            return 'fileupload-'.uniqid();
        }
        return $this->id;
    }

    /**
     * Возвращает HTML-атрибуты для поля загрузки файлов.
     *
     * @return array<string, string|int|bool|null> Ассоциативный массив атрибутов
     */
    public function getInputAttributes(): array
    {
        $attrs = array_merge([
            'id' => $this->getId(),
            'name' => $this->multiple ? $this->name.'[]' : $this->name,
            'type' => 'file',
            'class' => $this->classList(array_merge(['wallkit-fileupload__field'], $this->classes)),
            'accept' => $this->accept,
        ], $this->attributes);

        // Булевые атрибуты
        if ($this->required) {
            $attrs['required'] = true;
        }
        if ($this->disabled) {
            $attrs['disabled'] = true;
        }
        if ($this->multiple) {
            $attrs['multiple'] = true;
        }

        // Data-атрибуты для JS-валидации
        if ($this->maxSize !== null) {
            $attrs['data-max-size'] = $this->maxSize;
        }
        if ($this->maxFiles !== null) {
            $attrs['data-max-files'] = $this->maxFiles;
        }
        if ($this->maxWidth !== null) {
            $attrs['data-max-width'] = $this->maxWidth;
        }
        if ($this->maxHeight !== null) {
            $attrs['data-max-height'] = $this->maxHeight;
        }

        // Удаляем null-значения
        return array_filter($attrs, fn($value) => $value !== null);
    }
}