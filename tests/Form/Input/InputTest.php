<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Form\Input;

use OlegV\Exceptions\RenderException;
use OlegV\WallKit\Form\Input\Input;
use PHPUnit\Framework\TestCase;

class InputTest extends TestCase
{
    // ==================== ТЕСТЫ СОЗДАНИЯ ЭКЗЕМПЛЯРА ====================

    public function testCreatesBasicInput(): void
    {
        $input = new Input(
            name: 'username',
            placeholder: 'Введите имя',
            value: 'JohnDoe',
            type: 'text',
            required: true,
        );

        $this->assertEquals('username', $input->name);
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
        $invalid = new Input(name: 'test', type: 'invalid');

        $this->expectException(RenderException::class);
        $this->expectExceptionMessage('Неподдерживаемый тип: invalid');
        $invalid->renderOriginal();
    }

    // ==================== ТЕСТЫ МЕТОДОВ КЛАССА ====================

    public function testGetInputClasses(): void
    {
        // Базовый класс
        $input = new Input(name: 'test');
        $classes = $input->getInputClasses();

        $this->assertContains('wallkit-input__field', $classes);

        // С дополнительными классами
        $inputWithCustom = new Input(name: 'test', classes: ['custom-class', 'another-class']);
        $customClasses = $inputWithCustom->getInputClasses();

        $this->assertContains('wallkit-input__field', $customClasses);
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
        );

        $attributes = $input->getInputAttributes();

        $this->assertEquals('password', $attributes['type']);
        $this->assertArrayHasKey('class', $attributes);
        $this->assertStringContainsString('wallkit-input__field', $attributes['class']);
    }

    public function testGetInputAttributesFiltersNullValues(): void
    {
        $input = new Input(name: 'test');

        $attributes = $input->getInputAttributes();

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
    }

    // ==================== ТЕСТЫ ПОВЕДЕНИЯ КОМПОНЕНТА ====================

    public function testAutoGeneratesIdWhenNotProvided(): void
    {
        $input = new Input(name: 'username');

        $this->assertNull($input->id);
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
            placeholder: 'Test <b>bold</b>',
            value: $specialValue,
        );

        $this->assertEquals($specialValue, $input->value);
        $this->assertEquals('Test <b>bold</b>', $input->placeholder);
    }

    // ==================== ТЕСТЫ НА НЕГАТИВНЫЕ СЦЕНАРИИ ====================

    public function testEmptyNameThrowsException(): void
    {
        $input = new Input(name: '');

        $this->expectException(RenderException::class);
        $this->expectExceptionMessage('Имя поля обязательно и не может состоять только из пробелов');

        $input->renderOriginal();
    }

    public function testOnlySpacesNameThrowsException(): void
    {
        $input = new Input(name: '   ');

        $this->expectException(RenderException::class);
        $this->expectExceptionMessage('Имя поля обязательно и не может состоять только из пробелов');

        $input->renderOriginal();
    }

    public function testNullValuesAreAllowed(): void
    {
        $input = new Input(
            name: 'test',
            placeholder: null,
            value: null,
            id: null,
            pattern: null,
            min: null,
            max: null,
            step: null,
            autocomplete: null,
        );

        $this->assertNull($input->placeholder);
        $this->assertNull($input->value);
        $this->assertNull($input->id);
        $this->assertNull($input->pattern);
        $this->assertNull($input->min);
        $this->assertNull($input->max);
        $this->assertNull($input->step);
        $this->assertNull($input->autocomplete);
    }

    // ==================== ТЕСТЫ РЕНДЕРИНГА ====================

    public function testRendersBasicInput(): void
    {
        $input = new Input(name: 'test');

        $html = (string)$input;

        $this->assertStringStartsWith('<input', $html);
        $this->assertStringContainsString('name="test"', $html);
        $this->assertStringContainsString('type="text"', $html);
        $this->assertStringContainsString('wallkit-input__field', $html);
        $this->assertStringEndsWith('>', $html);
    }

    public function testRendersInputWithAllAttributes(): void
    {
        $input = new Input(
            name: 'email',
            placeholder: 'Enter email',
            value: 'test@example.com',
            type: 'email',
            required: true,
            id: 'email-field',
            maxLength: 255,
            classes: ['custom-class'],
            attributes: ['data-test' => 'value'],
        );

        $html = (string)$input;

        $this->assertStringContainsString('name="email"', $html);
        $this->assertStringContainsString('type="email"', $html);
        $this->assertStringContainsString('placeholder="Enter email"', $html);
        $this->assertStringContainsString('value="test@example.com"', $html);
        $this->assertStringContainsString('id="email-field"', $html);
        $this->assertStringContainsString('required', $html);
        $this->assertStringContainsString('maxlength="255"', $html);
        $this->assertStringContainsString('custom-class', $html);
        $this->assertStringContainsString('data-test="value"', $html);
    }

    public function testEscapesSpecialCharacters(): void
    {
        $input = new Input(
            name: 'xss',
            placeholder: '" onclick="alert(\'xss\')',
            value: '"><img src=x onerror=alert(1)>',
            attributes: [
                'data-xss' => '"><script>alert(1)</script>',
            ],
        );

        $html = (string)$input;

        // Проверяем экранирование
        $this->assertStringContainsString('&quot; onclick=&quot;alert(&apos;xss&apos;)', $html);
        $this->assertStringContainsString('&quot;&gt;&lt;img src=x onerror=alert(1)&gt;', $html);
        $this->assertStringContainsString(
            'data-xss="&quot;&gt;&lt;script&gt;alert(1)&lt;/script&gt;"',
            $html,
        );
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringNotContainsString('<img', $html);
    }
}