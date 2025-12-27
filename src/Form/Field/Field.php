<?php

declare(strict_types=1);

namespace OlegV\WallKit\Form\Field;

use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithInheritance;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;
use OlegV\WallKit\Form\Input\Input;

/**
 * Компонент Field - обёртка для поля ввода с label, help, error и toggle
 *
 * @example
 * $field = new Field(
 *     input: new Input(name: 'username'),
 *     label: 'Имя пользователя',
 *     helpText: 'Введите ваше имя'
 * );
 */
readonly class Field extends Base
{
    use WithHelpers;
    use WithStrictHelpers;
    use WithInheritance;

    /**
     * @param  Input  $input  Объект Input для рендеринга
     * @param  string|null  $label  Текст метки поля
     * @param  string|null  $helpText  Подсказка под полем
     * @param  string|null  $error  Сообщение об ошибке
     * @param  bool  $withPasswordToggle  Показывать кнопку toggle для паролей
     * @param  array<string>  $wrapperClasses  Дополнительные CSS классы для обёртки
     */
    public function __construct(
        public Input $input,
        public ?string $label = null,
        public ?string $helpText = null,
        public ?string $error = null,
        public bool $withPasswordToggle = true,
        public array $wrapperClasses = [],
    ) {
        parent::__construct();
    }

    /**
     * Получить CSS классы для обёртки поля
     * @return array<string>
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
     * Получить ID для связи label с input
     */
    public function getLabelId(): ?string
    {
        return $this->input->id;
    }

    /**
     * Нужно ли показывать toggle для пароля
     */
    public function shouldShowPasswordToggle(): bool
    {
        return $this->input->type === 'password' && $this->withPasswordToggle;
    }
}