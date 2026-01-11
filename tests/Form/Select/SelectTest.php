<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Form\Select;

use OlegV\Exceptions\RenderException;
use OlegV\WallKit\Form\Select\Select;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Тесты для компонента Select
 */
class SelectTest extends TestCase
{
    /**
     * Тест: компонент существует
     */
    public function testSelectClassExists(): void
    {
        $this->assertTrue(class_exists(Select::class));
    }

    /**
     * Тест: компонент является readonly
     */
    public function testSelectIsReadonly(): void
    {
        $reflection = new ReflectionClass(Select::class);
        $this->assertTrue($reflection->isReadOnly());
    }

    /**
     * Тест: простой селект рендерится без ошибок
     */
    public function testSimpleSelectRendersSuccessfully(): void
    {
        $select = new Select(
            name: 'country',
            options: [
                'ru' => 'Россия',
                'us' => 'США',
                'de' => 'Германия',
            ],
            selected: 'ru',
        );

        $html = (string) $select;
        $this->assertStringContainsString('<select', $html);
        $this->assertStringContainsString('name="country"', $html);
        $this->assertStringContainsString('value="ru"', $html);
        $this->assertStringContainsString('Россия', $html);
        $this->assertMatchesRegularExpression('/value="ru"[^>]*selected/', $html);
    }

    /**
     * Тест: селект с пустым именем выбрасывает исключение
     */
    public function testSelectWithEmptyNameThrowsException(): void
    {
        $this->expectException(RenderException::class);

        $select = new Select(
            name: '',
            options: ['ru' => 'Россия'],
        );

        $select->renderOriginal();
    }

    /**
     * Тест: множественный выбор без [] в имени выбрасывает исключение
     */
    public function testMultipleSelectWithoutBracketsThrowsException(): void
    {
        $this->expectException(RenderException::class);

        $select = new Select(
            name: 'skills',
            options: ['php' => 'PHP', 'js' => 'JavaScript'],
            selected: ['php'],
            multiple: true,
        );

        $select->renderOriginal();
    }

    /**
     * Тест: множественный выбор с [] в имени работает корректно
     */
    public function testMultipleSelectWithBracketsRendersCorrectly(): void
    {
        $select = new Select(
            name: 'skills[]',
            options: ['php' => 'PHP', 'js' => 'JavaScript', 'python' => 'Python'],
            selected: ['php', 'python'],
            multiple: true,
        );

        $html = (string) $select;
        $this->assertStringContainsString('name="skills[]"', $html);
        $this->assertStringContainsString('multiple', $html);
        $this->assertMatchesRegularExpression('/value="php"[^>]*selected/', $html);
        $this->assertMatchesRegularExpression('/value="python"[^>]*selected/', $html);
        $this->assertStringNotContainsString('value="js" selected', $html);
    }

    /**
     * Тест: селект с группами опций
     */
    public function testSelectWithOptionGroups(): void
    {
        $select = new Select(
            name: 'car',
            options: [
                'Немецкие' => [
                    'bmw' => 'BMW',
                    'audi' => 'Audi',
                ],
                'Японские' => [
                    'toyota' => 'Toyota',
                    'honda' => 'Honda',
                ],
            ],
            selected: 'audi',
        );

        $html = (string) $select;
        $this->assertStringContainsString('<optgroup label="Немецкие">', $html);
        $this->assertStringContainsString('<optgroup label="Японские">', $html);
        $this->assertMatchesRegularExpression('/value="audi"[^>]*selected/', $html);
    }

    /**
     * Тест: селект с плейсхолдером
     */
    public function testSelectWithPlaceholder(): void
    {
        $select = new Select(
            name: 'department',
            options: ['dev' => 'Разработка', 'sales' => 'Продажи'],
            placeholder: 'Выберите отдел',
        );

        $html = (string) $select;
        $this->assertStringContainsString('<option value="" disabled', $html);
        $this->assertStringContainsString('Выберите отдел', $html);
    }

    /**
     * Тест: обязательное поле
     */
    public function testRequiredSelect(): void
    {
        $select = new Select(
            name: 'country',
            options: ['ru' => 'Россия'],
            required: true,
        );

        $html = (string) $select;
        $this->assertStringContainsString('required', $html);
    }

    /**
     * Тест: отключенное поле
     */
    public function testDisabledSelect(): void
    {
        $select = new Select(
            name: 'country',
            options: ['ru' => 'Россия'],
            disabled: true,
        );

        $html = (string) $select;
        $this->assertStringContainsString('disabled', $html);
    }

    /**
     * Тест: автофокус
     */
    public function testAutoFocusSelect(): void
    {
        $select = new Select(
            name: 'country',
            options: ['ru' => 'Россия'],
            autoFocus: true,
        );

        $html = (string) $select;
        $this->assertStringContainsString('autofocus', $html);
    }

    /**
     * Тест: проверка выбранной опции (одиночный выбор)
     */
    public function testIsOptionSelectedForSingleSelect(): void
    {
        $select = new Select(
            name: 'country',
            options: ['ru' => 'Россия', 'us' => 'США'],
            selected: 'ru',
        );

        $this->assertTrue($select->isOptionSelected('ru'));
        $this->assertFalse($select->isOptionSelected('us'));
        $this->assertFalse($select->isOptionSelected('non-existent'));
    }

    /**
     * Тест: проверка выбранной опции (множественный выбор)
     */
    public function testIsOptionSelectedForMultipleSelect(): void
    {
        $select = new Select(
            name: 'skills[]',
            options: ['php' => 'PHP', 'js' => 'JavaScript', 'python' => 'Python'],
            selected: ['php', 'python'],
            multiple: true,
        );

        $this->assertTrue($select->isOptionSelected('php'));
        $this->assertTrue($select->isOptionSelected('python'));
        $this->assertFalse($select->isOptionSelected('js'));
    }

    /**
     * Тест: нормализация опций
     */
    public function testGetNormalizedOptions(): void
    {
        $select = new Select(
            name: 'test',
            options: [
                'ru' => 'Россия',
                'eu' => [
                    'de' => 'Германия',
                    'fr' => 'Франция',
                ],
            ],
        );

        $normalized = $select->getNormalizedOptions();

        $this->assertCount(3, $normalized);

        $this->assertEquals([
            'value' => 'ru',
            'label' => 'Россия',
            'group' => null,
        ], $normalized[0]);

        $this->assertEquals([
            'value' => 'de',
            'label' => 'Германия',
            'group' => 'eu',
        ], $normalized[1]);
    }

    /**
     * Тест: получение CSS классов
     */
    public function testGetSelectClasses(): void
    {
        $select = new Select(
            name: 'test',
            options: ['a' => 'A'],
            classes: ['custom-class'],
        );

        $classes = $select->getSelectClasses();

        $this->assertContains('wallkit-select__field', $classes);
        $this->assertContains('custom-class', $classes);
    }

    /**
     * Тест: получение CSS классов для множественного выбора
     */
    public function testGetSelectClassesForMultiple(): void
    {
        $select = new Select(
            name: 'test[]',
            options: ['a' => 'A'],
            multiple: true,
        );

        $classes = $select->getSelectClasses();
        $this->assertContains('wallkit-select__field--multiple', $classes);
    }

    /**
     * Тест: получение HTML атрибутов
     */
    public function testGetSelectAttributes(): void
    {
        $select = new Select(
            name: 'country',
            options: ['ru' => 'Россия'],
            required: true,
            disabled: false,
            id: 'country-select',
            attributes: ['data-test' => 'value'],
        );

        $attributes = $select->getSelectAttributes();

        $this->assertEquals('country-select', $attributes['id']);
        $this->assertEquals('country', $attributes['name']);
        $this->assertTrue($attributes['required']);
        $this->assertArrayNotHasKey('disabled', $attributes);
        $this->assertEquals('value', $attributes['data-test']);
    }

    /**
     * Тест: size для множественного выбора
     */
    public function testSizeForMultipleSelect(): void
    {
        $select = new Select(
            name: 'skills[]',
            options: ['php' => 'PHP', 'js' => 'JavaScript'],
            multiple: true,
            size: 5,
        );

        $attributes = $select->getSelectAttributes();
        $this->assertEquals(5, $attributes['size']);
    }

    /**
     * Тест: фильтрация null значений в атрибутах
     */
    public function testAttributesFilterNullValues(): void
    {
        $select = new Select(
            name: 'test',
            options: ['a' => 'A'],
            id: null,
        );

        $attributes = $select->getSelectAttributes();
        $this->assertArrayNotHasKey('id', $attributes);
    }

    /**
     * Тест: рендеринг пустого селекта
     */
    public function testEmptySelectRenders(): void
    {
        $select = new Select(
            name: 'empty',
            options: [],
        );

        $html = (string) $select;
        $this->assertStringContainsString('<select', $html);
        $this->assertStringContainsString('name="empty"', $html);
    }

    /**
     * Тест: сравнение строковых и числовых значений
     */
    public function testStringAndIntegerValueComparison(): void
    {
        $select = new Select(
            name: 'number',
            options: ['1' => 'One', 2 => 'Two'],
            selected: 1, // Передано как число
        );

        $this->assertTrue($select->isOptionSelected('1')); // Строка
        $this->assertTrue($select->isOptionSelected(1));   // Число

        $html = (string) $select;
        $this->assertMatchesRegularExpression('/value="1"[^>]*selected/', $html);
    }

    /**
     * Тест: массив selected для одиночного выбора (некорректный случай)
     */
    public function testArraySelectedForSingleSelect(): void
    {
        $select = new Select(
            name: 'country',
            options: ['ru' => 'Россия', 'us' => 'США'],
            selected: ['ru'], // Массив для одиночного выбора
        );

        // isOptionSelected должен вернуть false для массива в одиночном выборе
        $this->assertFalse($select->isOptionSelected('ru'));
        $this->assertFalse($select->isOptionSelected('us'));
    }
}
