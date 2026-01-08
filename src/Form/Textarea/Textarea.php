<?php

declare(strict_types=1);

namespace OlegV\WallKit\Form\Textarea;

use InvalidArgumentException;
use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithInheritance;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;

/**
 * Компонент Textarea - многострочное текстовое поле
 *
 * @property string $name
 * @property ?string $placeholder
 * @property ?string $value
 * @property int $rows
 * @property int|null $maxLength
 * @property bool $required
 * @property bool $disabled
 * @property bool $readonly
 * @property ?string $id
 * @property array<string> $classes
 * @property array<string, string|int|bool|null> $attributes
 * @property bool $autoFocus
 * @property ?string $autocomplete
 * @property ?bool $spellcheck
 */
readonly class Textarea extends Base
{
    use WithHelpers;
    use WithStrictHelpers;
    use WithInheritance;

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

    protected function prepare(): void
    {
        if (!$this->hasString(trim($this->name))) {
            throw new InvalidArgumentException("Имя поля обязательно");
        }
    }

    public function getTextareaClasses(): array
    {
        $classes = ['wallkit-textarea__field'];
        return array_merge($classes, $this->classes);
    }

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