<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Content\TagCloud;

use OlegV\WallKit\Content\TagCloud\TagCloud;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Тесты для компонента TagCloud
 */
class TagCloudTest extends TestCase
{
    // Тестовые данные
    private array $simpleTags = ['PHP', 'JavaScript', 'HTML', 'CSS', 'Python'];
    private array $tagsWithUrls = [
        'PHP' => '/tags/php',
        'JavaScript' => '/tags/js',
        'HTML' => '/tags/html',
    ];
    private array $tagsWithWeightsAndUrls = [
        'PHP' => ['url' => '/tags/php', 'weight' => 10],
        'JavaScript' => ['url' => '/tags/js', 'weight' => 8],
        'HTML' => ['weight' => 5],
    ];
    private array $mixedTags = [
        'PHP', // простой тег
        'JavaScript' => '/tags/js', // тег с URL
        'HTML' => ['url' => '/tags/html', 'weight' => 7], // тег с URL и весом
        'CSS' => ['weight' => 3], // тег только с весом
        'Python' => ['label' => 'Python Lang', 'url' => '/tags/python', 'weight' => 6], // полный массив
    ];

    /**
     * Тест: Класс TagCloud существует
     */
    public function testTagCloudClassExists(): void
    {
        $this->assertTrue(class_exists(TagCloud::class));
    }

    /**
     * Тест: TagCloud является readonly классом
     */
    public function testTagCloudIsReadonly(): void
    {
        $reflection = new ReflectionClass(TagCloud::class);
        $this->assertTrue($reflection->isReadOnly());
    }

    /**
     * Тест: Создание экземпляра с простыми тегами
     */
    public function testCreateWithSimpleTags(): void
    {
        $cloud = new TagCloud(tags: $this->simpleTags);

        $this->assertInstanceOf(TagCloud::class, $cloud);
        $this->assertEquals($this->simpleTags, $cloud->tags);
        $this->assertEquals('random', $cloud->sortBy);
        $this->assertTrue($cloud->autoSize);
    }

    /**
     * Тест: Создание с различными параметрами сортировки
     */
    public function testCreateWithDifferentSortOptions(): void
    {
        $cloud1 = new TagCloud(tags: $this->simpleTags, sortBy: 'weight');
        $cloud2 = new TagCloud(tags: $this->simpleTags, sortBy: 'alphabet');
        $cloud3 = new TagCloud(tags: $this->simpleTags, sortBy: 'random');

        $this->assertEquals('weight', $cloud1->sortBy);
        $this->assertEquals('alphabet', $cloud2->sortBy);
        $this->assertEquals('random', $cloud3->sortBy);
    }

    /**
     * Тест: Создание с отключенным autoSize
     */
    public function testCreateWithAutoSizeDisabled(): void
    {
        $cloud = new TagCloud(tags: $this->simpleTags, autoSize: false);

        $this->assertFalse($cloud->autoSize);
    }

    /**
     * Тест: Нормализация простых тегов
     */
    public function testNormalizeSimpleTags(): void
    {
        $cloud = new TagCloud(tags: $this->simpleTags);
        $processedTags = $cloud->getProcessedTags();

        $this->assertCount(5, $processedTags);

        foreach ($processedTags as $tag) {
            $this->assertArrayHasKey('label', $tag);
            $this->assertArrayHasKey('url', $tag);
            $this->assertArrayHasKey('weight', $tag);
            $this->assertArrayHasKey('sizeClass', $tag);
            $this->assertNull($tag['url']); // Простые теги без URL
            $this->assertEquals(1, $tag['weight']); // Вес по умолчанию
        }
    }

    /**
     * Тест: Нормализация тегов с URL
     */
    public function testNormalizeTagsWithUrls(): void
    {
        $cloud = new TagCloud(tags: $this->tagsWithUrls);
        $processedTags = $cloud->getProcessedTags();

        $this->assertCount(3, $processedTags);

        // Проверяем PHP тег
        $phpTag = array_filter($processedTags, fn($tag) => $tag['label'] === 'PHP');
        $phpTag = array_values($phpTag)[0] ?? null;

        $this->assertNotNull($phpTag);
        $this->assertEquals('/tags/php', $phpTag['url']);
        $this->assertEquals(1, $phpTag['weight']);
    }

    /**
     * Тест: Нормализация тегов с весами и URL
     */
    public function testNormalizeTagsWithWeightsAndUrls(): void
    {
        $cloud = new TagCloud(tags: $this->tagsWithWeightsAndUrls);
        $processedTags = $cloud->getProcessedTags();

        $this->assertCount(3, $processedTags);

        // Проверяем PHP тег
        $phpTag = array_filter($processedTags, fn($tag) => $tag['label'] === 'PHP');
        $phpTag = array_values($phpTag)[0] ?? null;

        $this->assertNotNull($phpTag);
        $this->assertEquals('/tags/php', $phpTag['url']);
        $this->assertEquals(10, $phpTag['weight']);

        // Проверяем HTML тег (без URL)
        $htmlTag = array_filter($processedTags, fn($tag) => $tag['label'] === 'HTML');
        $htmlTag = array_values($htmlTag)[0] ?? null;

        $this->assertNotNull($htmlTag);
        $this->assertNull($htmlTag['url']);
        $this->assertEquals(5, $htmlTag['weight']);
    }

    /**
     * Тест: Сортировка по весу
     */
    public function testSortByWeight(): void
    {
        $cloud = new TagCloud(tags: $this->tagsWithWeightsAndUrls, sortBy: 'weight');
        $processedTags = $cloud->getProcessedTags();

        // Теги должны быть отсортированы по убыванию веса
        $weights = array_column($processedTags, 'weight');
        $sortedWeights = $weights;
        rsort($sortedWeights);

        $this->assertEquals($sortedWeights, $weights);
        $this->assertEquals(10, $processedTags[0]['weight']); // PHP с весом 10
        $this->assertEquals(8, $processedTags[1]['weight']); // JavaScript с весом 8
        $this->assertEquals(5, $processedTags[2]['weight']); // HTML с весом 5
    }

    /**
     * Тест: Сортировка по алфавиту
     */
    public function testSortByAlphabet(): void
    {
        $cloud = new TagCloud(tags: ['Zebra', 'Apple', 'Banana', 'Cherry'], sortBy: 'alphabet');
        $processedTags = $cloud->getProcessedTags();

        $labels = array_column($processedTags, 'label');

        $this->assertEquals(['Apple', 'Banana', 'Cherry', 'Zebra'], $labels);
    }

    /**
     * Тест: Случайная сортировка
     */
    public function testSortByRandom(): void
    {
        $tags = ['A', 'B', 'C', 'D', 'E'];
        $cloud = new TagCloud(tags: $tags, sortBy: 'random');

        // Запускаем несколько раз, чтобы проверить что порядок меняется
        $differentOrders = false;
        $firstOrder = $cloud->getProcessedTags();

        for ($i = 0; $i < 10; $i++) {
            $cloud = new TagCloud(tags: $tags, sortBy: 'random');
            $newOrder = $cloud->getProcessedTags();

            if ($this->getTagLabels($firstOrder) !== $this->getTagLabels($newOrder)) {
                $differentOrders = true;
                break;
            }
        }

        // В большинстве случаев порядок должен быть разным
        $this->assertTrue($differentOrders, 'Случайная сортировка должна давать разный порядок');
    }

    /**
     * Тест: Назначение классов размеров при включенном autoSize
     */
    public function testAutoSizeClasses(): void
    {
        $tags = [
            'Small' => ['weight' => 1],
            'Medium' => ['weight' => 50],
            'Large' => ['weight' => 100],
        ];

        $cloud = new TagCloud(tags: $tags, sortBy: 'weight', autoSize: true);
        $processedTags = $cloud->getProcessedTags();

        $sizeClasses = array_column($processedTags, 'sizeClass');

        // Проверяем что назначены разные классы размеров
        $this->assertContains('wallkit-tagcloud__tag--xs', $sizeClasses);
        $this->assertContains('wallkit-tagcloud__tag--base', $sizeClasses);
        $this->assertContains('wallkit-tagcloud__tag--xl', $sizeClasses);
    }

    /**
     * Тест: Назначение классов размеров при отключенном autoSize
     */
    public function testNoAutoSizeClasses(): void
    {
        $tags = [
            'Small' => ['weight' => 1],
            'Large' => ['weight' => 100],
        ];

        $cloud = new TagCloud(tags: $tags, autoSize: false);
        $processedTags = $cloud->getProcessedTags();

        // Все теги должны иметь базовый класс размера
        foreach ($processedTags as $tag) {
            $this->assertEquals('wallkit-tagcloud__tag--base', $tag['sizeClass']);
        }
    }

    /**
     * Тест: Некорректный метод сортировки (через renderOriginal)
     */
    public function testInvalidSortByViaRenderOriginal(): void
    {
        // Начинаем отслеживать ошибки
        set_error_handler(function ($errno, $errstr) {
            throw new \Exception($errstr, $errno);
        }, E_USER_WARNING);

        try {
            $cloud = new TagCloud(tags: $this->simpleTags, sortBy: 'invalid_sort');

            // Вызываем prepare() напрямую, так как trigger_error() вызывается в нем
            $cloud->prepare();

            $this->fail('Должен был быть вызван trigger_error() с E_USER_WARNING');
        } catch (\Exception $e) {
            // Проверяем что это именно наша ошибка
            $this->assertStringContainsString('Неподдерживаемый тип сортировки', $e->getMessage());
            $this->assertStringContainsString('invalid_sort', $e->getMessage());
        } finally {
            // Восстанавливаем обработчик ошибок
            restore_error_handler();
        }
    }

    /**
     * Тест: Пустой массив тегов
     */
    public function testEmptyTagsArray(): void
    {
        $cloud = new TagCloud(tags: []);
        $processedTags = $cloud->getProcessedTags();

        $this->assertEmpty($processedTags);

        // Проверяем рендеринг
        $output = (string)$cloud;
        $this->assertStringContainsString('wallkit-tagcloud', $output);
        $this->assertStringNotContainsString('wallkit-tagcloud__tag', $output);
    }

    /**
     * Тест: Смешанные форматы тегов
     */
    public function testMixedTagFormats(): void
    {
        $cloud = new TagCloud(tags: $this->mixedTags);
        $processedTags = $cloud->getProcessedTags();

        $this->assertCount(5, $processedTags);

        // Проверяем разные форматы
        $labels = array_column($processedTags, 'label');
        $this->assertContains('PHP', $labels);
        $this->assertContains('JavaScript', $labels);
        $this->assertContains('HTML', $labels);
        $this->assertContains('CSS', $labels);
        $this->assertContains('Python Lang', $labels); // Кастомный label
    }

    /**
     * Тест: Теги с числовыми ключами
     */
    public function testTagsWithNumericKeys(): void
    {
        $tags = [
            0 => 'PHP',
            1 => 'JavaScript',
            'HTML' => '/tags/html', // строковый ключ
        ];

        $cloud = new TagCloud(tags: $tags);
        $processedTags = $cloud->getProcessedTags();

        $this->assertCount(3, $processedTags);

        $labels = array_column($processedTags, 'label');
        $this->assertContains('PHP', $labels);
        $this->assertContains('JavaScript', $labels);
        $this->assertContains('HTML', $labels);
    }

    /**
     * Тест: Подготовка (prepare) с валидными параметрами
     */
    public function testPrepareWithValidParameters(): void
    {
        $cloud = new TagCloud(tags: $this->simpleTags, sortBy: 'weight');

        // Не должно быть исключений
        $cloud->prepare();

        // Проверяем что компонент работает корректно
        $processedTags = $cloud->getProcessedTags();
        $this->assertNotEmpty($processedTags);
    }

    /**
     * Тест: Рендеринг компонента
     */
    public function testRenderComponent(): void
    {
        $cloud = new TagCloud(tags: $this->tagsWithWeightsAndUrls);
        $output = (string)$cloud;

        $this->assertStringStartsWith('<div class="wallkit-tagcloud">', trim($output));
        $this->assertStringEndsWith('</div>', trim($output));
        $this->assertStringContainsString('wallkit-tagcloud__tag', $output);

        // Проверяем наличие ссылок и span
        $this->assertStringContainsString('href="/tags/php"', $output);
        $this->assertStringContainsString('<span class="wallkit-tagcloud__tag', $output);
    }

    /**
     * Тест: CSS классы размеров в выводе
     */
    public function testSizeClassesInOutput(): void
    {
        $tags = [
            'Small' => ['weight' => 1],
            'Large' => ['weight' => 10],
        ];

        $cloud = new TagCloud(tags: $tags, autoSize: true);
        $output = (string)$cloud;

        // Должны присутствовать CSS классы размеров
        $this->assertStringContainsString('wallkit-tagcloud__tag--', $output);
    }

    /**
     * Тест: Экранирование специальных символов
     */
    public function testEscapingSpecialCharacters(): void
    {
        $tags = [
            'Test <script>alert("xss")</script>' => '/tags/xss',
            'Normal & Special > Characters' => null,
        ];

        $cloud = new TagCloud(tags: $tags);
        $output = (string)$cloud;

        // Проверяем что HTML символы экранированы
        $this->assertStringNotContainsString('<script>', $output);
        $this->assertStringContainsString('&lt;script&gt;', $output);
        $this->assertStringContainsString('&amp;', $output);
        $this->assertStringContainsString('&gt;', $output);
    }

    /**
     * Тест: Метод getProcessedTags возвращает корректную структуру
     */
    public function testGetProcessedTagsStructure(): void
    {
        $cloud = new TagCloud(tags: $this->simpleTags);
        $processedTags = $cloud->getProcessedTags();

        $this->assertIsArray($processedTags);

        if (!empty($processedTags)) {
            $firstTag = $processedTags[0];

            $this->assertArrayHasKey('label', $firstTag);
            $this->assertArrayHasKey('url', $firstTag);
            $this->assertArrayHasKey('weight', $firstTag);
            $this->assertArrayHasKey('sizeClass', $firstTag);

            $this->assertIsString($firstTag['label']);
            $this->assertTrue($firstTag['url'] === null || is_string($firstTag['url']));
            $this->assertIsInt($firstTag['weight']);
            $this->assertIsString($firstTag['sizeClass']);
            $this->assertStringStartsWith('wallkit-tagcloud__tag--', $firstTag['sizeClass']);
        }
    }

    /**
     * Тест: Одинаковые веса
     */
    public function testEqualWeights(): void
    {
        $tags = [
            'A' => ['weight' => 5],
            'B' => ['weight' => 5],
            'C' => ['weight' => 5],
        ];

        $cloud = new TagCloud(tags: $tags, autoSize: true);
        $processedTags = $cloud->getProcessedTags();

        // Все теги с одинаковым весом должны получить один размер
        $sizeClasses = array_unique(array_column($processedTags, 'sizeClass'));
        $this->assertCount(1, $sizeClasses);
    }

    /**
     * Тест: Один тег
     */
    public function testSingleTag(): void
    {
        $cloud = new TagCloud(tags: ['PHP']);
        $processedTags = $cloud->getProcessedTags();

        $this->assertCount(1, $processedTags);
        $this->assertEquals('wallkit-tagcloud__tag--base', $processedTags[0]['sizeClass']);
    }

    /**
     * Тест: Вес равный 0
     */
    public function testZeroWeight(): void
    {
        $tags = [
            'Zero' => ['weight' => 0],
            'Positive' => ['weight' => 10],
        ];

        $cloud = new TagCloud(tags: $tags, autoSize: true);
        $processedTags = $cloud->getProcessedTags();

        // Должны корректно обработаться веса
        $this->assertCount(2, $processedTags);

        $zeroTag = array_filter($processedTags, fn($tag) => $tag['label'] === 'Zero');
        $zeroTag = array_values($zeroTag)[0] ?? null;

        $this->assertNotNull($zeroTag);
        $this->assertEquals(0, $zeroTag['weight']);
        $this->assertEquals('wallkit-tagcloud__tag--xs', $zeroTag['sizeClass']);
    }

    /**
     * Тест: Отрицательные веса
     */
    public function testNegativeWeights(): void
    {
        $tags = [
            'Negative' => ['weight' => -5],
            'Zero' => ['weight' => 0],
            'Positive' => ['weight' => 10],
        ];

        $cloud = new TagCloud(tags: $tags, autoSize: true);
        $processedTags = $cloud->getProcessedTags();

        $this->assertCount(3, $processedTags);

        // Находим тег с отрицательным весом
        $negativeTag = array_filter($processedTags, fn($tag) => $tag['weight'] === -5);
        $negativeTag = array_values($negativeTag)[0] ?? null;

        $this->assertNotNull($negativeTag);
        $this->assertEquals(-5, $negativeTag['weight']);
    }

    /**
     * Вспомогательный метод: Получение меток тегов
     */
    private function getTagLabels(array $tags): array
    {
        return array_column($tags, 'label');
    }
}