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

        // Два класса компонента должен быть зарегистрированы - Input+родитель Base
        $this->assertEquals(2, $stats['cached_classes']);

        // Проверяем наличие CSS и JS ассетов
        $this->assertEquals(2, $stats['css_assets']);
        $this->assertEquals(0, $stats['js_assets']);
    }

    public function testRendersAssetsThroughManager(): void
    {
        new Input(name: 'test');

        $manager = BrickManager::getInstance();
        $assets = $manager->renderAssets();

        $this->assertStringContainsString('<style>', $assets);
        $this->assertStringContainsString('</style>', $assets);
        $this->assertStringNotContainsString('<script>', $assets);
        $this->assertStringNotContainsString('</script>', $assets);
        $this->assertStringContainsString('wallkit-input__field', $assets);
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

        $this->assertStringNotContainsString('<script>', $js);
        $this->assertStringNotContainsString('</script>', $js);
        $this->assertStringNotContainsString('<style>', $js);
    }

    // ==================== ТЕСТЫ КЭШИРОВАНИЯ ====================

    public function testComponentCaching(): void
    {
        $className = Input::class;
        $manager = BrickManager::getInstance();

        // Первый экземпляр должен закэшировать компонент
        $this->assertFalse($manager->isComponentMemoized($className));

        new Input(name: 'test');

        $this->assertTrue($manager->isComponentMemoized($className));

        $cached = $manager->getMemoizedComponent($className);
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

        // Все должны быть зарегистрированы два компонента Input и родитель - Base
        $stats = $manager->getStats();
        $this->assertEquals(2, $stats['cached_classes']); // Один класс компонента
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
        $input = new Input(
            name: 'original',
            placeholder: 'Введите значение',
            value: 'test value',
            type: 'email',
            required: true,
            disabled: false,
            readonly: false,
            id: 'test-id',
            autoFocus: true,
            pattern: '[a-z]+',
            min: '1',
            max: '100',
            maxLength: 50,
            minLength: 2,
            step: '1',
            autocomplete: 'email',
            spellcheck: true,
        );

        // Все свойства должны быть публичными и readonly
        $this->assertIsString($input->name);
        $this->assertEquals('original', $input->name);
        $this->assertEquals('Введите значение', $input->placeholder);
        $this->assertEquals('test value', $input->value);
        $this->assertEquals('email', $input->type);
        $this->assertTrue($input->required);
        $this->assertFalse($input->disabled);
        $this->assertFalse($input->readonly);
        $this->assertEquals('test-id', $input->id);
        $this->assertIsArray($input->classes);
        $this->assertIsArray($input->attributes);
        $this->assertTrue($input->autoFocus);
        $this->assertEquals('[a-z]+', $input->pattern);
        $this->assertEquals('1', $input->min);
        $this->assertEquals('100', $input->max);
        $this->assertEquals(50, $input->maxLength);
        $this->assertEquals(2, $input->minLength);
        $this->assertEquals('1', $input->step);
        $this->assertEquals('email', $input->autocomplete);
        $this->assertTrue($input->spellcheck);
    }

    // ==================== ТЕСТЫ ПРОИЗВОДИТЕЛЬНОСТИ ====================

    public function testMultipleRendersAreFast(): void
    {
        $startTime = microtime(true);

        // Рендерим много компонентов
        for ($i = 0; $i < 100; $i++) {
            $input = new Input(
                name: "field_$i",
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