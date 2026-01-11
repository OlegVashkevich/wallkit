<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Base;

use OlegV\Brick;
use OlegV\WallKit\Base\Base;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Тест базового класса WallKit
 */
class BaseTest extends TestCase
{
    public string $cssFilePath = __DIR__ . '/../../src/Base/style.css';

    public function testBaseClassExists(): void
    {
        $this->assertTrue(class_exists(Base::class));
    }

    public function testBaseIsReadonly(): void
    {
        $reflection = new ReflectionClass(Base::class);
        $this->assertTrue($reflection->isReadOnly());
    }

    public function testBaseExtendsBrick(): void
    {
        $this->assertInstanceOf(Brick::class, new Base());
    }

    public function testBaseRendersEmptyStringByDefault(): void
    {
        $base = new Base();
        $this->assertEquals('', (string) $base);
    }

    public function testBaseCssVariablesFileExists(): void
    {
        $this->assertFileExists($this->cssFilePath, 'CSS файл с переменными должен существовать');
    }

    public function testBaseCssVariablesAreDefined(): void
    {
        $cssContent = file_get_contents($this->cssFilePath);

        // Проверяем основные группы CSS-переменных из обновленного файла
        $expectedVariables = [
            // Цветовая система
            '--wk-color-gray-50',
            '--wk-color-gray-500',
            '--wk-color-gray-900',
            '--wk-color-primary',
            '--wk-color-secondary',
            '--wk-color-success',
            '--wk-color-danger',
            '--wk-color-warning',
            '--wk-color-info',
            '--wk-color-blue-500',
            '--wk-color-red-600',
            '--wk-color-green-500',
            '--wk-color-yellow-500',
            '--wk-color-purple-500',

            // Типографика
            '--wk-font-family',
            '--wk-font-size-xs',
            '--wk-font-size-base',
            '--wk-font-size-xl',
            '--wk-font-size-5xl',
            '--wk-font-weight-normal',
            '--wk-font-weight-bold',
            '--wk-line-height-base',

            // Отступы
            '--wk-spacing-1',
            '--wk-spacing-4',
            '--wk-spacing-8',
            '--wk-spacing-20',

            // Границы и радиусы
            '--wk-border-width',
            '--wk-border-radius',
            '--wk-border-radius-lg',

            // Тени
            '--wk-shadow-sm',
            '--wk-shadow',
            '--wk-shadow-lg',
            '--wk-shadow-outline',

            // Анимации
            '--wk-transition-fast',
            '--wk-transition',
            '--wk-transition-slow',
        ];

        foreach ($expectedVariables as $variable) {
            $this->assertStringContainsString(
                $variable . ':',
                $cssContent,
                "CSS переменная $variable должна быть определена",
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

        // Проверяем несколько ключевых переменных на корректность значений
        $lines = explode("\n", $cssContent);
        foreach ($lines as $line) {
            $trimmedLine = trim($line);

            if (str_contains($trimmedLine, '--wk-color-blue-500:')) {
                $this->assertStringContainsString(
                    'var(--wk-color-primary)',
                    $trimmedLine,
                    'Основной синий цвет должен ссылаться на primary цвет',
                );
            }

            if (str_contains($trimmedLine, '--wk-font-family:')) {
                $this->assertStringContainsString(
                    'system-ui',
                    $trimmedLine,
                    'Шрифтовая система должна включать system-ui',
                );
            }

            if (str_contains($trimmedLine, '--wk-font-size-base:')) {
                $this->assertStringContainsString(
                    '1rem',
                    $trimmedLine,
                    'Базовый размер шрифта должен быть 1rem',
                );
            }

            if (str_contains($trimmedLine, '--wk-color-primary:')) {
                $this->assertStringContainsString(
                    '#4a6fa5',
                    $trimmedLine,
                    'Основной цвет должен быть #4a6fa5',
                );
            }

            if (str_contains($trimmedLine, '--wk-color-danger:')) {
                $this->assertStringContainsString(
                    '#ef4444',
                    $trimmedLine,
                    'Цвет опасности должен быть #ef4444',
                );
            }

            if (str_contains($trimmedLine, '--wk-border-radius:')) {
                $this->assertStringContainsString(
                    '0.25rem',
                    $trimmedLine,
                    'Базовый радиус границы должен быть 0.25rem',
                );
            }
        }
    }

    public function testCssVariablesConsistency(): void
    {
        $cssContent = file_get_contents($this->cssFilePath);

        // Проверяем, что все цвета имеют значения в hex-формате или var()
        $colorPattern = '/(--wk-color-[a-zA-Z0-9-]+):\s*(#[0-9a-fA-F]{3,6}|var\(--wk-[a-zA-Z0-9-]+\))/';
        preg_match_all($colorPattern, $cssContent, $colorMatches);

        $this->assertGreaterThan(
            30,
            count($colorMatches[0]),
            'Должно быть много корректно определенных цветовых переменных',
        );
    }
}
