<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Form\Field;

use OlegV\BrickManager;
use OlegV\WallKit\Form\Field\Field;
use OlegV\WallKit\Form\Input\Input;
use PHPUnit\Framework\TestCase;

class FieldIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        BrickManager::getInstance()->clear();
    }

    public function testRegistersAssetsWithBrickManager(): void
    {
        echo new Field(
            input: new Input(name: 'test1'),
        );
        echo new Field(
            input: new Input(name: 'test2'),
        );

        $manager = BrickManager::getInstance();
        $stats = $manager->getStats();

        // Три типа компонентов должны быть закэшированы (Input и Field) и родитель - Base
        $this->assertEquals(3, $stats['cached_classes']);

        // Проверяем наличие CSS и JS ассетов
        $this->assertGreaterThan(0, $stats['css_assets']);
        $this->assertGreaterThan(0, $stats['js_assets']);
    }

    public function testMultipleInstancesUseSameCache(): void
    {
        $manager = BrickManager::getInstance();

        // Создаем несколько полей
        echo new Field(input: new Input(name: 'first'));
        echo new Field(input: new Input(name: 'second'));
        echo new Field(input: new Input(name: 'third'));

        // Все должны использовать кэш
        $stats = $manager->getStats();
        $this->assertEquals(3, $stats['cached_classes']); // Input и Field
    }
}
