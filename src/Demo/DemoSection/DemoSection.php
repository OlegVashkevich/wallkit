<?php

declare(strict_types=1);

namespace OlegV\WallKit\Demo\DemoSection;

use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithInheritance;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;

readonly class DemoSection extends Base
{
    use WithHelpers;
    use WithStrictHelpers;
    use WithInheritance;

    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public string $icon,
        public array $componentCards = [], // массив DemoComponentCard или HTML
        public ?string $extraContent = null,
    ) {
        parent::__construct();
    }
}