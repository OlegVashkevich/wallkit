<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Base;

use OlegV\WallKit\Base\Base;
use PHPUnit\Framework\TestCase;

/**
 * Тест базового класса WallKit
 */
class BaseTest extends TestCase
{
    public string $cssFilePath = __DIR__.'/../../src/Base/style.css';

    public function testBaseClassExists(): void
    {
        $this->assertTrue(class_exists(Base::class));
    }

    public function testBaseIsReadonly(): void
    {
        $reflection = new \ReflectionClass(Base::class);
        $this->assertTrue($reflection->isReadOnly());
    }

    public function testBaseExtendsBrick(): void
    {
        $this->assertInstanceOf(\OlegV\Brick::class, new Base());
    }

    public function testBaseRendersEmptyStringByDefault(): void
    {
        $base = new Base();
        $this->assertEquals('', (string)$base);
    }

    public function testBaseCssVariablesFileExists(): void
    {
        $this->assertFileExists($this->cssFilePath, 'CSS файл с переменными должен существовать');
    }

    public function testBaseCssVariablesAreDefined(): void
    {
        $cssContent = file_get_contents($this->cssFilePath);

        // Проверяем основные CSS-переменные
        $expectedVariables = [
            '--wk-black',
            '--wk-dark-gray',
            '--wk-medium-gray',
            '--wk-light-gray',
            '--wk-white',
            '--wk-border',
            '--wk-accent',
            '--wk-vis-accent',
            '--wk-nav-accent',
            '--wk-form-accent',
            '--wk-control-accent',
            '--wk-feedback-accent',
            '--wk-error-color',
            '--wk-success-color',
            '--wk-warning-color',
            '--wk-info-color',
            '--wk-font-family',
            '--wk-font-size-base',
            '--wk-spacing-4',
            '--wk-radius-md',
            '--wk-shadow-md',
            '--wk-transition-default',
        ];

        foreach ($expectedVariables as $variable) {
            $this->assertStringContainsString(
                $variable.':',
                $cssContent,
                "CSS переменная {$variable} должна быть определена",
            );
        }
    }

    public function testBaseCssVariablesHaveValidValues(): void
    {
        $cssContent = file_get_contents($this->cssFilePath);

        // Проверяем что переменные имеют значения (не пустые)
        $pattern = '/(--wk-[a-zA-Z0-9-]+)\s*:\s*([^;]+);/';
        preg_match_all($pattern, $cssContent, $matches);

        $this->assertGreaterThan(50, count($matches[1]), 'Должно быть много CSS переменных');

        // Проверяем несколько ключевых переменных
        $lines = explode("\n", $cssContent);
        foreach ($lines as $line) {
            if (str_contains($line, '--wk-color-blue-500:')) {
                $this->assertStringContainsString(
                    'var(--wk-accent)',
                    $line,
                    'Основной синий цвет должен быть var(--wk-accent)',
                );
            }

            if (str_contains($line, '--wk-font-family:')) {
                $this->assertStringContainsString("'Inter'", $line, 'Шрифт должен быть Inter');
            }

            if (str_contains($line, '--wk-font-size-base:')) {
                $this->assertStringContainsString('1rem', $line, 'Базовый размер шрифта должен быть 1rem');
            }
        }
    }
}