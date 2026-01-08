<?php

declare(strict_types=1);

namespace OlegV\WallKit\Content\TagCloud;

use OlegV\Traits\WithHelpers;
use OlegV\WallKit\Base\Base;

/**
 * Компонент облака тегов
 *
 * @property array<string, int> $tags Ассоциативный массив тегов [тег => количество]
 * @property string|null $activeTag Активный тег (если есть)
 * @property bool $showCount Показывать количество использования
 * @property bool $includeAllTag Включать тег "Все"
 * @property string $allTagText Текст для тега "Все"
 * @property string|null $title Заголовок облака
 * @property string|null $emptyMessage Сообщение при отсутствии тегов
 * @property bool $clickable Делать теги кликабельными
 * @property string $size Вариант размера (sm, md, lg)
 * @property string $variant Вариант отображения (cloud, list, pills)
 */
readonly class TagCloud extends Base
{
    use WithHelpers;

    /**
     * @param  array<string, int>  $tags  Ассоциативный массив тегов [тег => количество]
     */
    public function __construct(
        array $tags = [],
        ?string $activeTag = null,
        public bool $showCount = true,
        public bool $includeAllTag = true,
        public string $allTagText = 'Все',
        public ?string $title = 'Облако тегов',
        public ?string $emptyMessage = null,
        public bool $clickable = true,
        public string $size = 'md',
        public string $variant = 'cloud',
    ) {
        // Если нужно включить тег "Все", добавляем его в начало
        if ($includeAllTag && !empty($tags) && !isset($tags[$allTagText])) {
            $totalItems = array_sum($tags);
            $this->tags = [$allTagText => $totalItems] + $this->tags;
        }

        // Устанавливаем тег "Все" активным по умолчанию, если нет активного
        if ($activeTag === null && $includeAllTag && !empty($tags)) {
            $this->activeTag = $allTagText;
        }
    }

    /**
     * Получить CSS класс для размера тега на основе частоты использования
     */
    public function getTagSizeClass(int $count): string
    {
        if ($count > 10) {
            return 'wallkit-tag-cloud__tag--xl';
        }
        if ($count > 5) {
            return 'wallkit-tag-cloud__tag--lg';
        }
        if ($count > 2) {
            return 'wallkit-tag-cloud__tag--md';
        }
        return 'wallkit-tag-cloud__tag--sm';
    }

    /**
     * Проверить, активен ли тег
     */
    public function isTagActive(string $tag): bool
    {
        return $this->activeTag === $tag;
    }

    /**
     * Получить все CSS классы для тега
     *
     * @return array<string> Массив CSS классов
     */
    public function getTagClasses(string $tag, int $count): array
    {
        $classes = ['wallkit-tag-cloud__tag'];

        // Класс размера на основе частоты
        $classes[] = $this->getTagSizeClass($count);

        // Активный тег
        if ($this->isTagActive($tag)) {
            $classes[] = 'wallkit-tag-cloud__tag--active';
        }

        // Тег "Все"
        if ($tag === $this->allTagText) {
            $classes[] = 'wallkit-tag-cloud__tag--all';
        }

        // Кликабельность
        if ($this->clickable) {
            $classes[] = 'wallkit-tag-cloud__tag--clickable';
        }

        // Вариант отображения
        $classes[] = "wallkit-tag-cloud__tag--$this->variant";

        // Размер компонента
        $classes[] = "wallkit-tag-cloud__tag--size-$this->size";

        return $classes;
    }

    /**
     * Получить CSS классы для контейнера
     */
    public function getContainerClasses(): array
    {
        $classes = ['wallkit-tag-cloud'];
        $classes[] = "wallkit-tag-cloud--$this->variant";
        $classes[] = "wallkit-tag-cloud--size-$this->size";

        if ($this->clickable) {
            $classes[] = 'wallkit-tag-cloud--clickable';
        }

        return $classes;
    }

    /**
     * Проверить, есть ли теги для отображения
     */
    public function hasTags(): bool
    {
        return !empty($this->tags);
    }

    /**
     * Получить количество тегов (без учета тега "Все")
     */
    public function getTagsCount(): int
    {
        $count = count($this->tags);

        // Если есть тег "Все", исключаем его из подсчета
        if ($this->includeAllTag && isset($this->tags[$this->allTagText])) {
            $count--;
        }

        return $count;
    }
}