<?php

declare(strict_types=1);

namespace OlegV\WallKit\Content\TagCloud;

use OlegV\Traits\WithInheritance;
use OlegV\WallKit\Base\Base;

/**
 * Компонент TagCloud — облако тегов
 *
 * Этот компонент отображает набор тегов в виде облака, где размер каждого тега
 * зависит от его веса. Поддерживает различные форматы входных данных и методы сортировки.
 *
 * ## Примеры использования
 *
 * ### Простой список тегов
 * ```php
 * $tags = ['PHP', 'JavaScript', 'HTML', 'CSS', 'Python'];
 * $cloud = new TagCloud(tags: $tags);
 * echo $cloud;
 * ```
 *
 * ### Теги с ссылками и весами
 * ```php
 * $tags = [
 *     'PHP' => ['url' => '/tags/php', 'weight' => 10],
 *     'JavaScript' => ['url' => '/tags/js', 'weight' => 8],
 *     'HTML' => '/tags/html', // строка как URL
 *     'CSS' => ['weight' => 5], // без URL
 * ];
 * $cloud = new TagCloud(
 *     tags: $tags,
 *     sortBy: 'weight',
 *     autoSize: true
 * );
 * echo $cloud;
 * ```
 *
 * ### Случайный порядок тегов
 * ```php
 * $cloud = new TagCloud(
 *     tags: $tags,
 *     sortBy: 'random',
 *     autoSize: true
 * );
 * echo $cloud;
 * ```
 *
 * @package OlegV\WallKit\Content\TagCloud
 * @author OlegV
 * @since 1.0.0
 * @version 1.0.0
 * @immutable
 * @readonly
 */
readonly class TagCloud extends Base
{
    use WithInheritance;

    /**
     * Создаёт новый экземпляр компонента TagCloud.
     *
     * @param  array<string|array{url?: string, weight?: int, label?: string}>  $tags  Массив тегов.
     *     Поддерживаются различные форматы:
     *     - ['PHP', 'JavaScript'] — простые строки
     *     - ['PHP' => ['url' => '/tags/php', 'weight' => 10]] — тег с данными
     *     - ['PHP' => '/tags/php'] — тег с URL
     *     - ['PHP' => ['weight' => 5]] — тег без URL
     * @param  string  $sortBy  Метод сортировки тегов.
     *     Допустимые значения: 'weight' (по весу), 'alphabet' (по алфавиту), 'random' (случайно)
     * @param  bool  $autoSize  Включить ли автоматическое определение размера тегов на основе веса.
     *     Если false, все теги будут одинакового размера
     *
     */
    public function __construct(
        public array $tags,
        public string $sortBy = 'random',
        public bool $autoSize = true,
    ) {}

    /**
     * Подготовка компонента к рендерингу.
     *
     * Выполняет валидацию параметра `sortBy`.
     * Вызывается автоматически перед рендерингом компонента.
     *
     * @return void
     *
     * @internal
     */
    public function prepare(): void
    {
        // Только валидация sortBy
        if (!in_array($this->sortBy, ['weight', 'alphabet', 'random'], true)) {
            trigger_error(
                "Неподдерживаемый тип сортировки: $this->sortBy",
                E_USER_WARNING,
            );
        }
    }

    /**
     * Возвращает подготовленные для рендеринга теги.
     *
     * Метод нормализует входные данные, применяет сортировку и назначает CSS-классы
     * для размера тегов на основе их веса.
     *
     * @return array<array{
     *   label: string,
     *   url: string|null,
     *   weight: int,
     *   sizeClass: string
     * }> Массив тегов с нормализованными данными и CSS-классами размера
     *
     * @see TagCloud::normalizeTag() Для деталей нормализации
     * @see TagCloud::sortAndAssignClasses() Для логики сортировки и назначения классов
     */
    public function getProcessedTags(): array
    {
        $normalized = [];

        foreach ($this->tags as $key => $data) {
            // Если ключ не является строкой (числовой индекс), используем данные как метку
            if (!is_string($key)) {
                // $data может быть строкой (название тега) или массивом
                if (is_string($data)) {
                    // Простая строка без ключа: 'PHP'
                    $normalized[] = $this->normalizeTag($data, null);
                } elseif (is_array($data)) {
                    // Массив без строкового ключа: ['url' => '/tags/php', 'weight' => 5]
                    // Нужно извлечь label из массива или создать из url
                    $label = $data['label'] ?? basename($data['url'] ?? 'tag') ?? 'Тег';
                    $normalized[] = $this->normalizeTag($label, $data);
                }
            } else {
                // Строковый ключ: 'PHP' => данные
                $normalized[] = $this->normalizeTag($key, $data);
            }
        }

        $sortedTags = $this->sortTags($normalized);

        if ($this->autoSize) {
            return $this->assignSizeClasses($sortedTags);
        }

        // Если авто-размер отключен, добавляем базовый класс
        foreach ($sortedTags as &$tag) {
            $tag['sizeClass'] = 'wallkit-tagcloud__tag--base';
        }

        return $sortedTags;
    }

    /**
     * Нормализует данные одного тега.
     *
     * Преобразует различные форматы входных данных в единую структуру:
     * - Строка → тег без ссылки
     * - Строка со строковым ключом → тег со ссылкой
     * - Массив → тег с URL и/или весом
     *
     * @param  string  $label  Название тега
     * @param  mixed  $data  Данные тега (string|array|null)
     * @return array{label: string, url: string|null, weight: int} Нормализованный тег
     *
     * @internal
     */
    private function normalizeTag(string $label, mixed $data): array
    {
        // Если $data === null, значит это простая строка без данных
        if ($data === null) {
            return [
                'label' => $label,
                'url' => null,
                'weight' => 1,
            ];
        }

        // Если данные - строка, это URL
        if (is_string($data)) {
            return [
                'label' => $label,
                'url' => $data,
                'weight' => 1,
            ];
        }

        // Если данные - массив
        if (is_array($data)) {
            return [
                'label' => $data['label'] ?? $label,
                'url' => $data['url'] ?? null,
                'weight' => $data['weight'] ?? 1,
            ];
        }

        // Для любых других типов данных
        return [
            'label' => $label,
            'url' => null,
            'weight' => 1,
        ];
    }

    /**
     * Сортирует теги и назначает CSS-классы для размера.
     *
     * Выполняет сортировку в зависимости от `$sortBy`, нормализует веса
     * и назначает один из 5 CSS-классов размера на основе относительного веса тега.
     *
     * @param  array  $tags  Массив нормализованных тегов
     * @return array<array{
     *   label: string,
     *   url: string|null,
     *   weight: int,
     *   sizeClass: string
     * }> Отсортированные теги с назначенными CSS-классами
     *
     * @internal
     */
    private function sortAndAssignClasses(array $tags): array
    {
        // Сортировка
        $sortedTags = $this->sortTags($tags);

        if ($this->autoSize) {
            return $this->assignSizeClasses($sortedTags);
        }

        // Если авто-размер отключен, добавляем базовый класс
        foreach ($sortedTags as &$tag) {
            $tag['sizeClass'] = 'wallkit-tagcloud__tag--base';
        }

        return $sortedTags;
    }

    /**
     * Сортирует массив тегов в соответствии с выбранным методом.
     *
     * @param  array<array{label: string, url: string|null, weight: int}>  $tags  Массив тегов для сортировки
     * @return array<array{label: string, url: string|null, weight: int}> Отсортированный массив тегов
     *
     * @internal
     */
    private function sortTags(array $tags): array
    {
        if ($this->sortBy === 'weight') {
            usort($tags, fn($a, $b) => $b['weight'] <=> $a['weight']);
        } elseif ($this->sortBy === 'alphabet') {
            usort($tags, fn($a, $b) => $a['label'] <=> $b['label']);
        } else {
            shuffle($tags);
        }

        return $tags;
    }

    /**
     * Назначает CSS-классы размеров тегам на основе их весов.
     *
     * Нормализует веса в диапазоне от 0 до 1 и назначает один из 5 классов размера:
     * xs, sm, base, lg, xl.
     *
     * @param  array<array{label: string, url: string|null, weight: int}>  $tags  Массив тегов
     * @return array<array{label: string, url: string|null, weight: int, sizeClass: string}> Теги с назначенными классами размера
     *
     * @internal
     */
    private function assignSizeClasses(array $tags): array
    {
        // Если есть только один тег или все веса одинаковы
        if (count($tags) <= 1) {
            foreach ($tags as &$tag) {
                $tag['sizeClass'] = 'wallkit-tagcloud__tag--base';
            }
            return $tags;
        }

        // Нормализация весов и назначение CSS-классов
        $weights = array_column($tags, 'weight');
        $minWeight = min($weights);
        $maxWeight = max($weights);
        $weightRange = $maxWeight - $minWeight ?: 1;

        // Используем все доступные размеры из нашей системы
        $sizeClasses = [
            'wallkit-tagcloud__tag--xs',
            'wallkit-tagcloud__tag--sm',
            'wallkit-tagcloud__tag--base',
            'wallkit-tagcloud__tag--lg',
            'wallkit-tagcloud__tag--xl',
        ];

        $sizeCount = count($sizeClasses);

        foreach ($tags as &$tag) {
            $normalizedWeight = ($tag['weight'] - $minWeight) / $weightRange;
            $sizeIndex = min((int)($normalizedWeight * $sizeCount), $sizeCount - 1);

            $tag['sizeClass'] = $sizeClasses[$sizeIndex];
        }

        return $tags;
    }
}