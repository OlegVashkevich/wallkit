<?php

declare(strict_types=1);

namespace OlegV\WallKit\Demo\DemoComponentGrid;

use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithInheritance;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;

readonly class DemoComponentGrid extends Base
{
    use WithHelpers;
    use WithStrictHelpers;
    use WithInheritance;

    /**
     * @param  array<array{
     *     name: string,
     *     group: string,
     *     description: string,
     *     icon: string,
     *     demoFile: string,
     *     badge: string,
     *     status: string,
     *     since: string|null,
     *     tags: null|array<string>
     * }>  $components
     * @param  array<array{
     *     name: string,
     *     icon: string,
     *     color: string,
     *     description: string
     * }>  $groups
     */
    public function __construct(
        public array $components = [],
        public array $groups = [],
        public bool $showGroups = true,
        public bool $showStatus = true,
    ) {
        parent::__construct();
    }

    public function getComponentsByGroup(): array
    {
        $grouped = [];
        foreach ($this->components as $component) {
            $group = $component['group'];
            if (!isset($grouped[$group])) {
                $grouped[$group] = [];
            }
            $grouped[$group][] = $component;
        }
        return $grouped;
    }

    public function getGroupIcon(string $groupName): string
    {
        foreach ($this->groups as $group) {
            if ($group['name'] === $groupName) {
                return $group['icon'] ?? '📦';
            }
        }
        return '📦'; // Значок по умолчанию
    }

    public function getGroupColor(string $groupName): string
    {
        foreach ($this->groups as $group) {
            if ($group['name'] === $groupName) {
                return $group['color'] ?? 'var(--wk-accent)';
            }
        }
        return 'var(--wk-accent)';
    }

    public function getStatusClasses(string $status): array
    {
        $classes = ['wallkit-demo-component-grid__item-status'];
        $classes[] = "wallkit-demo-component-grid__item-status--$status";
        return $classes;
    }

    public function getBadgeClasses(string $badge): array
    {
        $classes = ['wallkit-demo-component-grid__item-badge'];
        $classes[] = "wallkit-demo-component-grid__item-badge--$badge";
        return $classes;
    }

    public function getGroupDescription(string $groupName): ?string
    {
        foreach ($this->groups as $group) {
            if ($group['name'] === $groupName) {
                return $group['description'] ?? null;
            }
        }
        return null;
    }

    public function getGroupTitle(string $groupName): ?string
    {
        foreach ($this->groups as $group) {
            if ($group['name'] === $groupName) {
                return $group['title'] ?? $groupName;
            }
        }
        return $groupName;
    }

    /**
     * Получить все уникальные теги из всех компонентов
     *
     * @param  bool  $includeAllTag  Добавить тег "Все"
     * @return array<string, int> Ассоциативный массив [тег => количество]
     */
    public function getAllTags(bool $includeAllTag = true): array
    {
        $allTags = [];

        foreach ($this->components as $component) {
            $tags = $component['tags'] ?? [];
            if (is_array($tags) && !empty($tags)) {
                foreach ($tags as $tag) {
                    $allTags[$tag] = ($allTags[$tag] ?? 0) + 1;
                }
            }
        }

        // Добавляем тег "Все" в начало
        if ($includeAllTag && !empty($allTags)) {
            $allTags = ['Все' => count($this->components)] + $allTags;
        }

        // Сортируем по количеству (от большего к меньшему)
        arsort($allTags);

        return $allTags;
    }

    /**
     * Получить CSS класс для размера тега в облаке
     *
     * @param  int  $count  Количество использования тега
     * @return string CSS класс для размера
     */
    public function getTagSizeClass(int $count): string
    {
        if ($count > 5) {
            return 'wallkit-demo-component-grid__tag--xl';
        }
        if ($count > 3) {
            return 'wallkit-demo-component-grid__tag--lg';
        }
        if ($count > 1) {
            return 'wallkit-demo-component-grid__tag--md';
        }
        return 'wallkit-demo-component-grid__tag--sm';
    }
}