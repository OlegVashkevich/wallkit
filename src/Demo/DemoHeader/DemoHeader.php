<?php

declare(strict_types=1);

namespace OlegV\WallKit\Demo\DemoHeader;

use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithInheritance;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;

readonly class DemoHeader extends Base
{
    use WithHelpers;
    use WithStrictHelpers;
    use WithInheritance;

    public function __construct(
        public string $title,
        public string $subtitle,
        public ?string $icon = null,
    ) {}
}
