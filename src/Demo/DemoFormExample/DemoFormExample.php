<?php

declare(strict_types=1);

namespace OlegV\WallKit\Demo\DemoFormExample;

use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithInheritance;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;

readonly class DemoFormExample extends Base
{
    use WithHelpers;
    use WithStrictHelpers;
    use WithInheritance;

    /**
     * @param  string  $title
     * @param  string  $description
     * @param  string  $formHtml
     * @param  array<array{text: string, variant: string, icon: string}>  $actions
     * @param  array<string, string>  $notes
     */
    public function __construct(
        public string $title,
        public string $description,
        public string $formHtml,
        public array $actions = [],
        public array $notes = [],
    ) {}

    /**
     * @return array<string, string>
     */
    public function getActionClasses(string $variant): array
    {
        $classes = ['wallkit-demo-form-example__action'];
        $classes[] = "wallkit-demo-form-example__action--$variant";
        return ['class' => $this->classList($classes)];
    }
}