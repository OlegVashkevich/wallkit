<?php

declare(strict_types=1);

namespace OlegV\WallKit\Form\Button;

use InvalidArgumentException;
use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithInheritance;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;

/**
 * Компонент Button — кнопка для действий в формах и интерфейсе
 *
 * Реализует HTML-кнопку с поддержкой различных типов, вариантов стилей,
 * размеров и состояний. Поддерживает как кнопки типа submit/reset для форм,
 * так и обычные кнопки для действий в интерфейсе.
 *
 * ## Примеры использования
 *
 * ### Основная кнопка
 * ```php
 * $button = new Button(
 *     text: 'Сохранить',
 *     type: 'submit',
 *     variant: 'primary'
 * );
 * echo $button;
 * ```
 *
 * ### Кнопка-ссылка
 * ```php
 * $linkButton = new Button(
 *     text: 'Перейти в профиль',
 *     href: '/profile',
 *     variant: 'link'
 * );
 * ```
 *
 * ### Кнопка с иконкой
 * ```php
 * $iconButton = new Button(
 *     text: 'Удалить',
 *     icon: '🗑️',
 *     variant: 'danger',
 *     size: 'sm'
 * );
 * ```
 *
 * @package OlegV\WallKit\Form\Button
 * @author OlegV
 * @since 1.0.0
 * @version 1.0.0
 * @immutable
 * @readonly
 */
readonly class Button extends Base
{
    use WithHelpers;
    use WithStrictHelpers;
    use WithInheritance;

    /**
     * Создаёт новый экземпляр компонента Button.
     *
     * @param  string  $text  Текст кнопки
     * @param  string  $type  Тип кнопки (button, submit, reset)
     * @param  string  $variant  Вариант стиля (primary, secondary, success, danger, warning, info, light, dark, link)
     * @param  string  $size  Размер (sm, md, lg)
     * @param  bool  $disabled  Отключена ли кнопка
     * @param  string|null  $icon  Иконка (emoji или текст перед текстом кнопки)
     * @param  string|null  $iconAfter  Иконка после текста кнопки
     * @param  string|null  $href  Ссылка (если указана, рендерится как <a> вместо <button>)
     * @param  string|null  $target  Цель для ссылки (_self, _blank, _parent, _top)
     * @param  string|null  $id  HTML ID кнопки
     * @param  array<string>  $classes  Дополнительные CSS классы
     * @param  array<string, string|int|bool|null>  $attributes  Дополнительные HTML атрибуты
     * @param  string|null  $onClick  JavaScript обработчик onclick
     * @param  bool  $fullWidth  Кнопка на всю ширину
     * @param  bool  $outline  Контурный вариант (outline)
     * @param  bool  $rounded  Закруглённые углы
     *
     * @throws \InvalidArgumentException Если передан недопустимый тип кнопки
     * @throws \InvalidArgumentException Если передан недопустимый вариант стиля
     */
    public function __construct(
        public string $text,
        public string $type = 'button',
        public string $variant = 'primary',
        public string $size = 'md',
        public bool $disabled = false,
        public ?string $icon = null,
        public ?string $iconAfter = null,
        public ?string $href = null,
        public ?string $target = null,
        public ?string $id = null,
        public array $classes = [],
        public array $attributes = [],
        public ?string $onClick = null,
        public bool $fullWidth = false,
        public bool $outline = false,
        public bool $rounded = false,
    ) {}

    /**
     * Подготовка компонента к рендерингу.
     *
     * Выполняет валидацию параметров компонента перед использованием.
     *
     * @return void
     *
     * @throws \InvalidArgumentException Если тип кнопки не поддерживается
     * @throws \InvalidArgumentException Если вариант стиля не поддерживается
     * @throws \InvalidArgumentException Если размер не поддерживается
     *
     * @internal
     */
    protected function prepare(): void
    {
        $validTypes = ['button', 'submit', 'reset'];
        if (! in_array($this->type, $validTypes, true)) {
            throw new InvalidArgumentException("Неподдерживаемый тип кнопки: $this->type");
        }

        $validVariants = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link'];
        if (! in_array($this->variant, $validVariants, true)) {
            throw new InvalidArgumentException("Неподдерживаемый вариант стиля: $this->variant");
        }

        $validSizes = ['sm', 'md', 'lg'];
        if (! in_array($this->size, $validSizes, true)) {
            throw new InvalidArgumentException("Неподдерживаемый размер: $this->size");
        }
    }

    /**
     * Возвращает массив CSS-классов для кнопки.
     *
     * @return array<string> Массив CSS-классов
     */
    public function getButtonClasses(): array
    {
        $classes = ['wallkit-button'];

        // Базовый класс по варианту
        if ($this->outline) {
            $classes[] = "wallkit-button--outline-$this->variant";
        } else {
            $classes[] = "wallkit-button--$this->variant";
        }

        // Размер
        $classes[] = "wallkit-button--$this->size";

        // Модификаторы
        if ($this->disabled) {
            $classes[] = 'wallkit-button--disabled';
        }

        if ($this->fullWidth) {
            $classes[] = 'wallkit-button--full-width';
        }

        if ($this->rounded) {
            $classes[] = 'wallkit-button--rounded';
        }

        // Пользовательские классы
        return array_merge($classes, $this->classes);
    }

    /**
     * Возвращает все HTML-атрибуты для кнопки.
     *
     * @return array<string, string|int|bool|null> Ассоциативный массив атрибутов
     */
    public function getButtonAttributes(): array
    {
        $attrs = array_merge([
            'id' => $this->id,
            'class' => $this->classList($this->getButtonClasses()),
        ], $this->attributes);

        if ($this->href === null) {
            // Для обычной кнопки
            $attrs['type'] = $this->type;
            if ($this->disabled) {
                $attrs['disabled'] = true;
            }
        } else {
            // Для кнопки-ссылки
            $attrs['href'] = $this->href;
            if ($this->target !== null) {
                $attrs['target'] = $this->target;
            }
        }
        if ($this->onClick !== null) {
            $attrs['onclick'] = $this->onClick;
        }

        // Удаляем null значения
        return array_filter($attrs, fn ($value) => $value !== null);
    }

    /**
     * Определяет, является ли кнопка ссылкой.
     *
     * @return bool true если кнопка должна рендериться как ссылка
     */
    public function isLink(): bool
    {
        return $this->href !== null;
    }
}
