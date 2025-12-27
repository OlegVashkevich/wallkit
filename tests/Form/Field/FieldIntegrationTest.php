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
        new Field(
            input: new Input(name: 'test1'),
        );
        new Field(
            input: new Input(name: 'test2'),
        );

        $manager = BrickManager::getInstance();
        $stats = $manager->getStats();

        // Два типа компонентов должны быть закэшированы (Input и Field)
        $this->assertEquals(2, $stats['cached_classes']);

        // Проверяем наличие CSS и JS ассетов
        $this->assertGreaterThan(0, $stats['css_assets']);
        $this->assertGreaterThan(0, $stats['js_assets']);
    }

    public function testMultipleInstancesUseSameCache(): void
    {
        $manager = BrickManager::getInstance();

        // Создаем несколько полей
        $field1 = new Field(input: new Input(name: 'first'));
        $field2 = new Field(input: new Input(name: 'second'));
        $field3 = new Field(input: new Input(name: 'third'));

        // Все должны использовать кэш
        $stats = $manager->getStats();
        $this->assertEquals(2, $stats['cached_classes']); // Input и Field
    }
}