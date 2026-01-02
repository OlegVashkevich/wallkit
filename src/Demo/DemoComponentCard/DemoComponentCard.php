<?php

declare(strict_types=1);

namespace OlegV\WallKit\Demo\DemoComponentCard;

use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithInheritance;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;

readonly class DemoComponentCard extends Base
{
    use WithHelpers;
    use WithStrictHelpers;
    use WithInheritance;

    public function __construct(
        public string $title,
        public string $componentHtml,
        public string $description,
        public string $code,
        public string $badgeText,
        public string $badgeType = 'default',
        public ?string $note = null,
    ) {
        parent::__construct();
    }

    public function getBadgeClasses(): array
    {
        $classes = ['wallkit-demo-component-card__badge'];
        $classes[] = "wallkit-demo-component-card__badge--$this->badgeType";
        return $classes;
    }
}