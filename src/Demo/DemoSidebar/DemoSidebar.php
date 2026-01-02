<?php

declare(strict_types=1);

namespace OlegV\WallKit\Demo\DemoSidebar;

use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithInheritance;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;

readonly class DemoSidebar extends Base
{
    use WithHelpers;
    use WithStrictHelpers;
    use WithInheritance;

    /**
     * @param  array<array{title: string, href: string, icon: string, active: bool}>  $navItems
     * @param  array<array{title: string, content: string, icon: string}>  $infoCards
     */
    public function __construct(
        public array $navItems = [],
        public array $infoCards = [],
        public ?string $title = 'Навигация',
    ) {
        parent::__construct();
    }

    /**
     * @return array<array{classes: string[], attrs: array<string, string>}>
     */
    public function getNavItemAttributes(int $index): array
    {
        $item = $this->navItems[$index];
        $classes = ['wallkit-demo-sidebar__nav-item'];

        if ($item['active'] ?? false) {
            $classes[] = 'wallkit-demo-sidebar__nav-item--active';
        }

        $attrs = [
            'href' => $item['href'],
            'data-section' => str_replace('#', '', $item['href']),
        ];

        return ['classes' => $classes, 'attrs' => $attrs];
    }
}