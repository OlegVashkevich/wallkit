<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Form\Input;

use OlegV\BrickManager;
use OlegV\WallKit\Form\Input\Input;
use PHPUnit\Framework\TestCase;

class InputIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Очищаем менеджер перед каждым тестом
        BrickManager::getInstance()->clear();
    }

    // ==================== ТЕСТЫ ИНТЕГРАЦИИ С BRICKMANAGER ====================

    public function testRegistersAssetsWithBrickManager(): void
    {
        $input1 = new Input(name: 'test1');
        $input2 = new Input(name: 'test2');

        $manager = BrickManager::getInstance();
        $stats = $manager->getStats();

        // Один тип компонента должны быть закэширован
        $this->assertEquals(1, $stats['cached_classes']);

        // Проверяем наличие CSS и JS ассетов
        $this->assertGreaterThan(0, $stats['css_assets']);
        $this->assertGreaterThan(0, $stats['js_assets']);
    }

    public function testRendersAssetsThroughManager(): void
    {
        new Input(name: 'test');

        $manager = BrickManager::getInstance();
        $assets = $manager->renderAssets();

        $this->assertStringContainsString('<style>', $assets);
        $this->assertStringContainsString('</style>', $assets);
        $this->assertStringContainsString('<script>', $assets);
        $this->assertStringContainsString('</script>', $assets);
        $this->assertStringContainsString('wallkit-input', $assets);
    }

    public function testSeparateCssAndJsRendering(): void
    {
        new Input(name: 'test');

        $manager = BrickManager::getInstance();
        $css = $manager->renderCss();
        $js = $manager->renderJs();

        $this->assertStringContainsString('<style>', $css);
        $this->assertStringContainsString('</style>', $css);
        $this->assertStringNotContainsString('<script>', $css);

        $this->assertStringContainsString('<script>', $js);
        $this->assertStringContainsString('</script>', $js);
        $this->assertStringNotContainsString('<style>', $js);
    }

    // ==================== ТЕСТЫ КЭШИРОВАНИЯ ====================

    public function testComponentCaching(): void
    {
        $className = Input::class;
        $manager = BrickManager::getInstance();

        // Первый экземпляр должен закэшировать компонент
        $this->assertFalse($manager->isComponentCached($className));

        new Input(name: 'test');

        $this->assertTrue($manager->isComponentCached($className));

        $cached = $manager->getCachedComponent($className);
        $this->assertIsArray($cached);
        $this->assertArrayHasKey('dir', $cached);
        $this->assertArrayHasKey('templatePath', $cached);
        $this->assertArrayHasKey('css', $cached);
        $this->assertArrayHasKey('js', $cached);
        $this->assertFileExists($cached['templatePath']);
    }

    public function testMultipleInstancesUseSameCache(): void
    {
        $className = Input::class;
        $manager = BrickManager::getInstance();

        // Создаем несколько экземпляров
        $input1 = new Input(name: 'first');
        $input2 = new Input(name: 'second');
        $input3 = new Input(name: 'third');

        // Все должны использовать один кэш
        $stats = $manager->getStats();
        $this->assertEquals(1, $stats['cached_classes']); // Один класс компонента
    }

    // ==================== ТЕСТЫ НАСЛЕДОВАНИЯ ТРЕЙТОВ ====================

    public function testUsesWithHelpersTrait(): void
    {
        $input = new Input(name: 'test');

        // Проверяем наличие методов из WithHelpers
        $this->assertTrue(method_exists($input, 'e'));
        $this->assertTrue(method_exists($input, 'classList'));
        $this->assertTrue(method_exists($input, 'attr'));
        $this->assertTrue(method_exists($input, 'number'));
        $this->assertTrue(method_exists($input, 'date'));
        $this->assertTrue(method_exists($input, 'json'));
        $this->assertTrue(method_exists($input, 'truncate'));
        $this->assertTrue(method_exists($input, 'url'));
        $this->assertTrue(method_exists($input, 'uniqueId'));
        $this->assertTrue(method_exists($input, 'wordCount'));
        $this->assertTrue(method_exists($input, 'plural'));
    }

    public function testUsesWithInheritanceTrait(): void
    {
        $input = new Input(name: 'test');

        // Проверяем наличие метода из WithInheritance
        $this->assertTrue(method_exists($input, 'initializeComponent'));

        // Проверяем, что компонент корректно использует наследование
        $this->assertInstanceOf(\OlegV\WallKit\Base\Base::class, $input);
    }

    // ==================== ТЕСТЫ READONLY СЕМАНТИКИ ====================

    public function testInputIsReadonlyClass(): void
    {
        $input = new Input(name: 'test');

        $reflection = new \ReflectionClass($input);
        $this->assertTrue($reflection->isReadOnly());
    }

    public function testPropertiesAreImmutable(): void
    {
        $input = new Input(name: 'original');

        // Все свойства должны быть публичными и readonly
        $this->assertIsString($input->name);
        $this->assertNull($input->label);
        $this->assertNull($input->placeholder);
        $this->assertNull($input->value);
        $this->assertEquals('text', $input->type);
        $this->assertFalse($input->required);
        $this->assertFalse($input->disabled);
        $this->assertFalse($input->readonly);
        $this->assertNull($input->id);
        $this->assertIsArray($input->classes);
        $this->assertIsArray($input->attributes);
        $this->assertNull($input->helpText);
        $this->assertNull($input->error);
        $this->assertFalse($input->autoFocus);
        $this->assertNull($input->pattern);
        $this->assertNull($input->min);
        $this->assertNull($input->max);
        $this->assertNull($input->maxLength);
        $this->assertNull($input->minLength);
        $this->assertNull($input->step);
        $this->assertTrue($input->withPasswordToggle);
        $this->assertNull($input->autocomplete);
        $this->assertNull($input->spellcheck);
    }

    // ==================== ТЕСТЫ ПРОИЗВОДИТЕЛЬНОСТИ ====================

    public function testMultipleRendersAreFast(): void
    {
        $startTime = microtime(true);

        // Рендерим много компонентов
        for ($i = 0; $i < 100; $i++) {
            $input = new Input(
                name: "field_$i",
                label: "Поле $i",
                value: "Значение $i",
            );
            $result = (string)$input;
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // 100 рендеров должны выполняться быстро
        $this->assertLessThan(
            0.5,
            $executionTime,
            "100 renders took {$executionTime} seconds, expected < 0.5s",
        );
    }

    public function testCacheImprovesPerformance(): void
    {
        // Первый рендер (с компиляцией)
        $firstStart = microtime(true);
        $input = new Input(name: 'test');
        $firstRender = (string)$input;
        $firstEnd = microtime(true);
        $firstTime = $firstEnd - $firstStart;

        // Второй рендер (из кэша)
        $secondStart = microtime(true);
        $sameInput = new Input(name: 'test2'); // Другой экземпляр, тот же класс
        $secondRender = (string)$sameInput;
        $secondEnd = microtime(true);
        $secondTime = $secondEnd - $secondStart;

        // Второй рендер должен быть быстрее (использует кэш)
        $this->assertLessThan(
            $firstTime,
            $secondTime,
            "Cached render ({$secondTime}s) should be faster than first render ({$firstTime}s)",
        );

        // Результаты должны быть разными (разные name)
        $this->assertNotEquals($firstRender, $secondRender);
    }
}