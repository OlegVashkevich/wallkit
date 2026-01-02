<?php

declare(strict_types=1);

namespace OlegV\WallKit\Demo\DemoLayout;

use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithInheritance;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;

readonly class DemoLayout extends Base
{
    use WithHelpers;
    use WithStrictHelpers;
    use WithInheritance;

    public function __construct(
        public string $sidebar,
        public string $content,
        public bool $sidebarLeft = true,
        public string $sidebarWidth = '280px',
    ) {
        parent::__construct();
    }

    public function getGridTemplate(): string
    {
        if ($this->sidebarLeft) {
            return "$this->sidebarWidth 1fr";
        }
        return "1fr $this->sidebarWidth";
    }
}