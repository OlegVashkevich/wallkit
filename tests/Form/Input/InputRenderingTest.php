<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Form\Input;

use OlegV\WallKit\Form\Input\Input;
use PHPUnit\Framework\TestCase;

class InputRenderingTest extends TestCase
{
    // ==================== ТЕСТЫ РЕНДЕРИНГА ====================

    public function testRendersBasicInput(): void
    {
        $input = new Input(
            name: 'username',
            id: 'username-field',
        );

        $html = (string)$input;

        $this->assertStringStartsWith('<input', $html);
        $this->assertStringContainsString('name="username"', $html);
        $this->assertStringContainsString('id="username-field"', $html);
        $this->assertStringContainsString('wallkit-input__field', $html);
        $this->assertStringContainsString('type="text"', $html);
        $this->assertStringEndsWith('>', $html);
    }

    public function testRendersRequiredInput(): void
    {
        $input = new Input(
            name: 'email',
            required: true,
            id: 'email-field',
        );

        $html = (string)$input;

        $this->assertStringContainsString('required', $html);
        $this->assertStringContainsString('name="email"', $html);
    }

    public function testRendersDisabledInput(): void
    {
        $input = new Input(
            name: 'disabled-field',
            disabled: true,
        );

        $html = (string)$input;

        $this->assertStringContainsString('disabled', $html);
        $this->assertStringContainsString('name="disabled-field"', $html);
    }

    public function testRendersReadonlyInput(): void
    {
        $input = new Input(
            name: 'readonly-field',
            readonly: true,
        );

        $html = (string)$input;

        $this->assertStringContainsString('readonly', $html);
        $this->assertStringContainsString('name="readonly-field"', $html);
    }

    public function testRendersInputWithAutoFocus(): void
    {
        $input = new Input(
            name: 'search',
            autoFocus: true,
        );

        $html = (string)$input;

        $this->assertStringContainsString('autofocus', $html);
        $this->assertStringContainsString('name="search"', $html);
    }

    public function testRendersInputWithPlaceholder(): void
    {
        $input = new Input(
            name: 'search',
            placeholder: 'Поиск...',
        );

        $html = (string)$input;

        $this->assertStringContainsString('placeholder="Поиск..."', $html);
    }

    public function testRendersInputWithValue(): void
    {
        $input = new Input(
            name: 'username',
            value: 'JohnDoe',
        );

        $html = (string)$input;

        $this->assertStringContainsString('value="JohnDoe"', $html);
    }

    public function testRendersInputWithCustomAttributes(): void
    {
        $input = new Input(
            name: 'custom',
            attributes: [
                'data-custom' => 'value',
                'aria-label' => 'Custom input',
                'title' => 'Tooltip text',
            ],
        );

        $html = (string)$input;

        $this->assertStringContainsString('data-custom="value"', $html);
        $this->assertStringContainsString('aria-label="Custom input"', $html);
        $this->assertStringContainsString('title="Tooltip text"', $html);
    }

    public function testRendersInputWithCustomClasses(): void
    {
        $input = new Input(
            name: 'styled',
            classes: ['custom-class', 'another-class', 'mb-3'],
        );

        $html = (string)$input;

        $this->assertStringContainsString('custom-class', $html);
        $this->assertStringContainsString('another-class', $html);
        $this->assertStringContainsString('mb-3', $html);
        $this->assertStringContainsString('wallkit-input__field', $html);
    }

    // ==================== ТЕСТЫ БЕЗОПАСНОСТИ ====================

    public function testEscapesSpecialCharacters(): void
    {
        $input = new Input(
            name: 'xss',
            placeholder: '" onclick="alert(\'xss\')',
            value: '"><img src=x onerror=alert(1)>',
            attributes: [
                'data-xss' => '<script>alert("xss")</script>',
            ],
        );

        $html = (string)$input;

        // Проверяем экранирование в значениях
        $this->assertStringContainsString('&quot; onclick=&quot;alert(&apos;xss&apos;)', $html);
        $this->assertStringContainsString('&quot;&gt;&lt;img src=x onerror=alert(1)&gt;', $html);
        $this->assertStringContainsString(
            'data-xss="&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;"',
            $html,
        );

        // Проверяем, что опасные теги экранированы
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringNotContainsString('</script>', $html);
        $this->assertStringNotContainsString('<img', $html);
    }

    public function testPreventsXssInAttributes(): void
    {
        $input = new Input(
            name: 'test',
            attributes: [
                'onclick' => 'alert("xss")',
                'onerror' => 'alert(1)',
                'onload' => 'alert(2)',
                'style' => 'background: url(javascript:alert(1))',
            ],
        );

        $html = (string)$input;

        // Проверяем, что опасные атрибуты фильтруются
        $this->assertStringNotContainsString('onclick=', $html);
        $this->assertStringNotContainsString('onerror=', $html);
        $this->assertStringNotContainsString('onload=', $html);

        // Проверяем, что style не фильтруется (он безопасен в контексте экранирования)
        $this->assertStringContainsString('style=', $html);
    }

    public function testPreventsJavascriptInUrlAttributes(): void
    {
        $input = new Input(
            name: 'test',
            attributes: [
                'href' => 'javascript:alert(1)',
                'src' => 'javascript:alert(2)',
                'action' => 'javascript:alert(3)',
                'formaction' => 'javascript:alert(4)',
            ],
        );

        $html = (string)$input;

        // Проверяем, что javascript: URL фильтруются
        $this->assertStringNotContainsString('javascript:', $html);
        $this->assertStringNotContainsString('href=', $html); // Атрибут должен быть удален полностью
        $this->assertStringNotContainsString('src=', $html);
        $this->assertStringNotContainsString('action=', $html);
        $this->assertStringNotContainsString('formaction=', $html);
    }

    // ==================== ТЕСТЫ РАЗНЫХ ТИПОВ ПОЛЕЙ ====================

    public function testRendersDifferentInputTypes(): void
    {
        $types = [
            'email' => 'email@example.com',
            'number' => '42',
            'tel' => '+79991234567',
            'url' => 'https://example.com',
            'search' => 'query',
            'date' => '2024-01-01',
            'color' => '#ff0000',
            'password' => 'secret',
            'range' => '50',
            'file' => '',
            'hidden' => 'hidden-value',
        ];

        foreach ($types as $type => $testValue) {
            $input = new Input(
                name: $type,
                value: $testValue !== '' ? $testValue : null,
                type: $type,
            );

            $html = (string)$input;

            $this->assertStringContainsString(
                'type="'.$type.'"',
                $html,
                "Failed for type: $type",
            );

            if ($testValue !== '' && $type !== 'file') {
                $this->assertStringContainsString(
                    'value="'.$testValue.'"',
                    $html,
                    "Failed to render value for type: $type",
                );
            }
        }
    }

    public function testRendersInputWithValidationAttributes(): void
    {
        $input = new Input(
            name: 'user',
            pattern: '[A-Za-z]{3,}',
            min: '1',
            max: '100',
            maxLength: 50,
            minLength: 3,
            step: '0.5',
        );

        $html = (string)$input;

        $this->assertStringContainsString('pattern="[A-Za-z]{3,}"', $html);
        $this->assertStringContainsString('min="1"', $html);
        $this->assertStringContainsString('max="100"', $html);
        $this->assertStringContainsString('maxlength="50"', $html);
        $this->assertStringContainsString('minlength="3"', $html);
        $this->assertStringContainsString('step="0.5"', $html);
    }

    public function testRendersInputWithAutocompleteAndSpellcheck(): void
    {
        $input = new Input(
            name: 'email',
            autocomplete: 'email',
            spellcheck: true,
        );

        $html = (string)$input;

        $this->assertStringContainsString('autocomplete="email"', $html);
        $this->assertStringContainsString('spellcheck="true"', $html);
    }

    // ==================== ТЕСТЫ HTML СТРУКТУРЫ ====================

    public function testRendersOnlyInputElement(): void
    {
        $input = new Input(name: 'test');

        $html = (string)$input;

        // Должен быть только один input элемент
        $this->assertStringStartsWith('<input', $html);
        $this->assertStringEndsWith('>', $html);
        $this->assertDoesNotMatchRegularExpression('/<div/', $html);
        $this->assertDoesNotMatchRegularExpression('/<\/div>/', $html);
        $this->assertDoesNotMatchRegularExpression('/<label/', $html);
        $this->assertDoesNotMatchRegularExpression('/<\/label>/', $html);
        $this->assertDoesNotMatchRegularExpression('/<span/', $html);
        $this->assertDoesNotMatchRegularExpression('/<\/span>/', $html);
    }

    public function testDoesNotRenderEmptyAttributes(): void
    {
        $input = new Input(name: 'test');

        $html = (string)$input;

        // Проверяем, что null атрибуты не рендерятся
        $this->assertStringNotContainsString('id="', $html);
        $this->assertStringNotContainsString('placeholder="', $html);
        $this->assertStringNotContainsString('value="', $html);
        $this->assertStringNotContainsString('autocomplete="', $html);
        $this->assertStringNotContainsString('spellcheck="', $html);
        $this->assertStringNotContainsString('pattern="', $html);
        $this->assertStringNotContainsString('min="', $html);
        $this->assertStringNotContainsString('max="', $html);
        $this->assertStringNotContainsString('maxlength="', $html);
        $this->assertStringNotContainsString('minlength="', $html);
        $this->assertStringNotContainsString('step="', $html);
        $this->assertStringNotContainsString('required', $html);
        $this->assertStringNotContainsString('disabled', $html);
        $this->assertStringNotContainsString('readonly', $html);
        $this->assertStringNotContainsString('autofocus', $html);
    }

    public function testRendersAllAttributesWhenProvided(): void
    {
        $input = new Input(
            name: 'test',
            placeholder: 'Enter value',
            value: 'Test value',
            required: true,
            disabled: true,
            readonly: true,
            id: 'test-id',
            autoFocus: true,
            pattern: '.*',
            min: '0',
            max: '100',
            maxLength: 10,
            minLength: 1,
            step: '1',
            autocomplete: 'on',
            spellcheck: false,
        );

        $html = (string)$input;

        // Проверяем, что все атрибуты рендерятся
        $this->assertStringContainsString('id="test-id"', $html);
        $this->assertStringContainsString('placeholder="Enter value"', $html);
        $this->assertStringContainsString('value="Test value"', $html);
        $this->assertStringContainsString('autocomplete="on"', $html);
        $this->assertStringContainsString('spellcheck="false"', $html);
        $this->assertStringContainsString('pattern=".*"', $html);
        $this->assertStringContainsString('min="0"', $html);
        $this->assertStringContainsString('max="100"', $html);
        $this->assertStringContainsString('maxlength="10"', $html);
        $this->assertStringContainsString('minlength="1"', $html);
        $this->assertStringContainsString('step="1"', $html);
        $this->assertStringContainsString('required', $html);
        $this->assertStringContainsString('disabled', $html);
        $this->assertStringContainsString('readonly', $html);
        $this->assertStringContainsString('autofocus', $html);
    }

    // ==================== ТЕСТЫ КОМБИНАЦИЙ ====================

    public function testCombinesDefaultAndCustomClasses(): void
    {
        $input = new Input(
            name: 'test',
            classes: ['additional-class'],
        );

        $html = (string)$input;

        $this->assertStringContainsString('wallkit-input__field', $html);
        $this->assertStringContainsString('additional-class', $html);
        // Проверяем, что классы объединены в один атрибут class
        $this->assertMatchesRegularExpression('/class="[^"]*wallkit-input__field[^"]*additional-class[^"]*"/', $html);
    }

    public function testMergesDefaultAndCustomAttributes(): void
    {
        $input = new Input(
            name: 'test',
            attributes: [
                'data-custom' => 'value',
                'aria-label' => 'Custom',
            ],
        );

        $html = (string)$input;

        $this->assertStringContainsString('data-custom="value"', $html);
        $this->assertStringContainsString('aria-label="Custom"', $html);
        $this->assertStringContainsString('name="test"', $html);
        $this->assertStringContainsString('type="text"', $html);
    }

    public function testCustomAttributesOverrideDefaults(): void
    {
        $input = new Input(
            name: 'test',
            type: 'email',
            attributes: [
                'type' => 'tel', // Должен переопределить type="email" на type="tel"
            ],
        );

        $html = (string)$input;

        $this->assertStringContainsString('type="tel"', $html);
        $this->assertStringNotContainsString('type="email"', $html);
    }
}