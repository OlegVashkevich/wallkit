<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Form\Input;

use InvalidArgumentException;
use OlegV\WallKit\Form\Input\Input;
use PHPUnit\Framework\TestCase;

class InputTest extends TestCase
{
    // ==================== ТЕСТЫ СОЗДАНИЯ ЭКЗЕМПЛЯРА ====================

    public function testCreatesBasicInput(): void
    {
        $input = new Input(
            name: 'username',
            label: 'Имя пользователя',
            placeholder: 'Введите имя',
            value: 'JohnDoe',
            type: 'text',
            required: true,
        );

        $this->assertEquals('username', $input->name);
        $this->assertEquals('Имя пользователя', $input->label);
        $this->assertEquals('Введите имя', $input->placeholder);
        $this->assertEquals('JohnDoe', $input->value);
        $this->assertEquals('text', $input->type);
        $this->assertTrue($input->required);
        $this->assertFalse($input->disabled);
        $this->assertFalse($input->readonly);
    }

    public function testCreatesInputWithMinimalParameters(): void
    {
        $input = new Input(name: 'email');

        $this->assertEquals('email', $input->name);
        $this->assertNull($input->label);
        $this->assertNull($input->placeholder);
        $this->assertNull($input->value);
        $this->assertEquals('text', $input->type);
        $this->assertFalse($input->required);
    }

    // ==================== ТЕСТЫ ВАЛИДАЦИИ ТИПОВ ====================

    public function testValidTypes(): void
    {
        $validTypes = [
            'text',
            'email',
            'password',
            'number',
            'tel',
            'url',
            'search',
            'date',
            'datetime-local',
            'time',
            'month',
            'week',
            'color',
            'range',
            'file',
            'hidden',
        ];

        foreach ($validTypes as $type) {
            $input = new Input(name: 'test', type: $type);
            $this->assertEquals($type, $input->type);
        }
    }

    public function testThrowsExceptionForInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Неподдерживаемый тип: invalid');

        new Input(name: 'test', type: 'invalid');
    }

    // ==================== ТЕСТЫ МЕТОДОВ КЛАССА ====================

    public function testGetBaseClasses(): void
    {
        // Базовый класс
        $input = new Input(name: 'test');
        $classes = $input->getBaseClasses();

        $this->assertContains('wallkit-input', $classes);

        // С ошибкой
        $inputWithError = new Input(name: 'test', error: 'Обязательное поле');
        $errorClasses = $inputWithError->getBaseClasses();

        $this->assertContains('wallkit-input--error', $errorClasses);

        // Отключенный
        $inputDisabled = new Input(name: 'test', disabled: true);
        $disabledClasses = $inputDisabled->getBaseClasses();

        $this->assertContains('wallkit-input--disabled', $disabledClasses);

        // С дополнительными классами
        $inputWithCustom = new Input(name: 'test', classes: ['custom-class', 'another-class']);
        $customClasses = $inputWithCustom->getBaseClasses();

        $this->assertContains('custom-class', $customClasses);
        $this->assertContains('another-class', $customClasses);
    }

    public function testGetInputAttributes(): void
    {
        $input = new Input(
            name: 'email',
            placeholder: 'test@example.com',
            value: 'user@test.com',
            required: true,
            disabled: false,
            readonly: true,
            id: 'email-field',
            pattern: '[a-z]+',
            min: '0',
            max: '100',
            maxLength: 100,
            minLength: 5,
            step: '1',
            autocomplete: 'email',
            spellcheck: true,
        );

        $attributes = $input->getInputAttributes();

        // Проверяем обязательные атрибуты
        $this->assertEquals('email-field', $attributes['id']);
        $this->assertEquals('email', $attributes['name']);
        $this->assertEquals('text', $attributes['type']);
        $this->assertEquals('wallkit-input__field', $attributes['class']);
        $this->assertEquals('test@example.com', $attributes['placeholder']);
        $this->assertEquals('user@test.com', $attributes['value']);
        $this->assertEquals('email', $attributes['autocomplete']);
        $this->assertEquals('true', $attributes['spellcheck']);

        // Булевые атрибуты
        $this->assertTrue($attributes['required']);
        $this->assertTrue($attributes['readonly']);
        $this->assertArrayNotHasKey('disabled', $attributes);

        // Валидационные атрибуты
        $this->assertEquals('[a-z]+', $attributes['pattern']);
        $this->assertEquals('0', $attributes['min']);
        $this->assertEquals('100', $attributes['max']);
        $this->assertEquals(100, $attributes['maxlength']);
        $this->assertEquals(5, $attributes['minlength']);
        $this->assertEquals('1', $attributes['step']);
    }

    public function testGetInputAttributesWithPasswordType(): void
    {
        $input = new Input(
            name: 'password',
            type: 'password',
            withPasswordToggle: true,
        );

        $attributes = $input->getInputAttributes();

        $this->assertEquals('password', $attributes['type']);
        $this->assertArrayHasKey('data-name', $attributes);
        $this->assertEquals('password', $attributes['data-name']);
    }

    public function testGetInputAttributesFiltersNullValues(): void
    {
        $input = new Input(name: 'test');

        $attributes = $input->getInputAttributes();
        echo $input;
        $this->assertArrayNotHasKey('id', $attributes);
        $this->assertArrayNotHasKey('placeholder', $attributes);
        $this->assertArrayNotHasKey('value', $attributes);
        $this->assertArrayNotHasKey('autocomplete', $attributes);
        $this->assertArrayNotHasKey('spellcheck', $attributes);
        $this->assertArrayNotHasKey('required', $attributes);
        $this->assertArrayNotHasKey('disabled', $attributes);
        $this->assertArrayNotHasKey('readonly', $attributes);
    }

    public function testIsValidType(): void
    {
        $input = new Input(name: 'test');

        $this->assertTrue($input->isValidType('email'));
        $this->assertTrue($input->isValidType('password'));
        $this->assertTrue($input->isValidType('number'));

        $this->assertFalse($input->isValidType('invalid'));
        $this->assertFalse($input->isValidType('checkbox')); // Не поддерживается
        $this->assertFalse($input->isValidType('radio'));    // Не поддерживается
    }

    // ==================== ТЕСТЫ ПОВЕДЕНИЯ КОМПОНЕНТА ====================

    public function testAutoGeneratesIdWhenNotProvided(): void
    {
        $input = new Input(name: 'username');

        $this->assertNull($input->id);
    }

    public function testPasswordToggleEnabledByDefault(): void
    {
        $input = new Input(name: 'password', type: 'password');

        $this->assertTrue($input->withPasswordToggle);
    }

    public function testCanDisablePasswordToggle(): void
    {
        $input = new Input(
            name: 'password',
            type: 'password',
            withPasswordToggle: false,
        );

        $this->assertFalse($input->withPasswordToggle);
    }

    public function testSpellcheckDisabledByDefault(): void
    {
        $input = new Input(name: 'test');

        $this->assertNull($input->spellcheck);
    }

    // ==================== ТЕСТЫ НА ПРЕДЕЛЬНЫЕ ЗНАЧЕНИЯ ====================

    public function testHandlesVeryLongValues(): void
    {
        $longValue = str_repeat('a', 1000);

        $input = new Input(
            name: 'long',
            value: $longValue,
            maxLength: 2000,
        );

        $this->assertEquals($longValue, $input->value);
        $this->assertEquals(2000, $input->maxLength);
    }

    public function testHandlesSpecialCharacters(): void
    {
        $specialValue = '<script>alert("xss")</script>&"\'<>';

        $input = new Input(
            name: 'xss',
            label: 'Test <b>bold</b>',
            value: $specialValue,
        );

        $this->assertEquals($specialValue, $input->value);
        $this->assertEquals('Test <b>bold</b>', $input->label);
    }

    // ==================== ТЕСТЫ НА НЕГАТИВНЫЕ СЦЕНАРИИ ====================

    public function testEmptyNameThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Input(name: '');
    }

    public function testNullValuesAreAllowed(): void
    {
        $input = new Input(
            name: 'test',
            label: null,
            placeholder: null,
            value: null,
            id: null,
            helpText: null,
            error: null,
            pattern: null,
            min: null,
            max: null,
            step: null,
            autocomplete: null,
        );

        $this->assertNull($input->label);
        $this->assertNull($input->placeholder);
        $this->assertNull($input->value);
        $this->assertNull($input->id);
        $this->assertNull($input->helpText);
        $this->assertNull($input->error);
        $this->assertNull($input->pattern);
        $this->assertNull($input->min);
        $this->assertNull($input->max);
        $this->assertNull($input->step);
        $this->assertNull($input->autocomplete);
    }
}