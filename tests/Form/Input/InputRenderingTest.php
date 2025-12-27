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
            label: 'Имя пользователя',
            id: 'username-field',
        );

        $html = (string)$input;

        $this->assertStringContainsString('wallkit-input', $html);
        $this->assertStringContainsString('name="username"', $html);
        $this->assertStringContainsString('Имя пользователя', $html);
        $this->assertStringContainsString('id="username-field"', $html);
        $this->assertStringContainsString('wallkit-input__label', $html);
        $this->assertStringContainsString('wallkit-input__field', $html);
    }

    public function testRendersRequiredInput(): void
    {
        $input = new Input(
            name: 'email',
            label: 'Email',
            required: true,
            id: 'email-field',
        );

        $html = (string)$input;

        $this->assertStringContainsString('required', $html);
        $this->assertStringContainsString('wallkit-input__required', $html);
        $this->assertStringContainsString('*', $html);
    }

    public function testRendersInputWithError(): void
    {
        $input = new Input(
            name: 'password',
            label: 'Пароль',
            error: 'Пароль слишком короткий',
        );

        $html = (string)$input;

        $this->assertStringContainsString('wallkit-input--error', $html);
        $this->assertStringContainsString('wallkit-input__error', $html);
        $this->assertStringContainsString('Пароль слишком короткий', $html);
        $this->assertStringContainsString('⚠️', $html);
    }

    public function testRendersInputWithHelpText(): void
    {
        $input = new Input(
            name: 'phone',
            label: 'Телефон',
            helpText: 'В формате +7 (XXX) XXX-XX-XX',
        );

        $html = (string)$input;

        $this->assertStringContainsString('wallkit-input__help', $html);
        $this->assertStringContainsString('В формате +7 (XXX) XXX-XX-XX', $html);
    }

    public function testRendersInputWithoutLabel(): void
    {
        $input = new Input(name: 'search');

        $html = (string)$input;

        $this->assertStringNotContainsString('wallkit-input__label', $html);
        $this->assertStringContainsString('wallkit-input__field', $html);
    }

    public function testRendersInputWithoutIdButWithLabel(): void
    {
        $input = new Input(
            name: 'comment',
            label: 'Комментарий',
        );

        $html = (string)$input;

        // Должен использовать div вместо label[for]
        $this->assertStringContainsString('<div class="wallkit-input__label"', $html);
        $this->assertStringNotContainsString('for="', $html);
    }

    public function testRendersPasswordInputWithToggle(): void
    {
        $input = new Input(
            name: 'password',
            type: 'password',
            withPasswordToggle: true,
        );

        $html = (string)$input;

        $this->assertStringContainsString('type="password"', $html);
        $this->assertStringContainsString('wallkit-input__toggle-password', $html);
        $this->assertStringContainsString('Показать/скрыть пароль', $html);
    }

    public function testRendersPasswordInputWithoutToggle(): void
    {
        $input = new Input(
            name: 'password',
            type: 'password',
            withPasswordToggle: false,
        );

        $html = (string)$input;

        $this->assertStringContainsString('type="password"', $html);
        $this->assertStringNotContainsString('wallkit-input__toggle-password', $html);
    }

    public function testRendersDisabledInput(): void
    {
        $input = new Input(
            name: 'disabled-field',
            label: 'Неактивное поле',
            disabled: true,
        );

        $html = (string)$input;

        $this->assertStringContainsString('disabled', $html);
        $this->assertStringContainsString('wallkit-input--disabled', $html);
    }

    public function testRendersReadonlyInput(): void
    {
        $input = new Input(
            name: 'readonly-field',
            label: 'Только чтение',
            readonly: true,
        );

        $html = (string)$input;

        $this->assertStringContainsString('readonly', $html);
    }

    public function testRendersInputWithAutoFocus(): void
    {
        $input = new Input(
            name: 'search',
            autoFocus: true,
        );

        $html = (string)$input;

        $this->assertStringContainsString('autofocus', $html);
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
    }

    // ==================== ТЕСТЫ БЕЗОПАСНОСТИ ====================

    public function testEscapesSpecialCharacters(): void
    {
        $input = new Input(
            name: 'xss',
            label: '<script>alert("xss")</script>',
            placeholder: '" onclick="alert(\'xss\')',
            value: '"><img src=x onerror=alert(1)>',
            helpText: '<b>bold</b> & "quotes"</div><script>alert(1)</script>',
        );

        $html = (string)$input;

        // Проверяем, что HTML специальные символы экранированы
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringNotContainsString('</script>', $html);
        $this->assertStringNotContainsString('<b>', $html);
        $this->assertStringNotContainsString('<img', $html);

        // Проверяем, что они экранированы правильно
        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringContainsString('&lt;/script&gt;', $html);
        $this->assertStringContainsString('&lt;b&gt;', $html);
        $this->assertStringContainsString('&lt;/div&gt;', $html);
        $this->assertStringContainsString('&lt;img', $html);

        // Проверяем экранирование кавычек
        $this->assertStringContainsString('&quot;', $html);
        $this->assertStringContainsString('&apos;', $html); // для одинарных кавычек

        // Проверяем экранирование амперсанда
        $this->assertStringContainsString('&amp;', $html);

        // Текст "onerror=" остаётся как есть, потому что это не специальный символ
        // Но он безопасен, потому что находится внутри экранированного значения атрибута
        $this->assertStringContainsString('onerror=', $html);

        // Проверяем, что текст "onerror=" находится внутри экранированного контекста
        // Он должен быть после &lt;img и перед &gt;
        $this->assertMatchesRegularExpression(
            '/&lt;img src=x onerror=alert\(1\)&gt;/',
            $html,
            'XSS вектор должен быть полностью экранирован как текст, а не как HTML',
        );
    }

    public function testPreventsXssInAttributes(): void
    {
        $input = new Input(
            name: 'test',
            attributes: [
                'data-xss' => '"><script>alert(1)</script>',
                'onclick' => 'alert("xss")',
                'style' => 'background: url(javascript:alert(1))',
            ],
        );

        $html = (string)$input;

        $this->assertStringNotContainsString('"><script>', $html);
        $this->assertStringNotContainsString('onclick=', $html);
        $this->assertStringContainsString('&quot;&gt;&lt;script&gt;', $html);
    }

    // ==================== ТЕСТЫ РАЗНЫХ ТИПОВ ПОЛЕЙ ====================

    public function testRendersDifferentInputTypes(): void
    {
        $types = [
            'email' => 'Email',
            'number' => 'Число',
            'tel' => 'Телефон',
            'url' => 'URL',
            'search' => 'Поиск',
            'date' => 'Дата',
            'color' => 'Цвет',
        ];

        foreach ($types as $type => $label) {
            $input = new Input(
                name: $type,
                label: $label,
                type: $type,
            );

            $html = (string)$input;

            $this->assertStringContainsString(
                'type="'.$type.'"',
                $html,
                "Failed for type: $type",
            );
            $this->assertStringContainsString($label, $html);
        }
    }

    // ==================== ТЕСТЫ HTML СТРУКТУРЫ ====================

    public function testHtmlStructureIsValid(): void
    {
        // Тест БЕЗ ошибки, чтобы helpText показывался
        $input = new Input(
            name: 'test',
            label: 'Test Label',
            id: 'test-id',
            helpText: 'Help text',
        // БЕЗ error!
        );

        $html = (string)$input;

        // Проверяем базовую структуру
        $this->assertStringStartsWith('<div class="wallkit-input', $html);
        $this->assertStringEndsWith('</div>', $html);

        // Проверяем наличие всех секций (кроме error)
        $this->assertStringContainsString('<label', $html);
        $this->assertStringContainsString('<input', $html);
        $this->assertStringContainsString('wallkit-input__help', $html);
        $this->assertStringNotContainsString('wallkit-input__error', $html); // Не должно быть ошибки
        $this->assertStringNotContainsString('wallkit-input--error', $html); // И класса ошибки тоже

        // Проверяем порядок элементов
        $labelPos = strpos($html, 'wallkit-input__label');
        $inputPos = strpos($html, 'wallkit-input__field');
        $helpPos = strpos($html, 'wallkit-input__help');

        $this->assertLessThan($inputPos, $labelPos, 'Label should come before input');
        $this->assertLessThan($helpPos, $inputPos, 'Input should come before help');
    }

    public function testHtmlStructureWithError(): void
    {
        // Отдельный тест для случая с ошибкой
        $input = new Input(
            name: 'test',
            label: 'Test Label',
            id: 'test-id',
            helpText: 'Help text',
            error: 'Error message', // С ошибкой!
        );

        $html = (string)$input;

        // Проверяем базовую структуру
        $this->assertStringStartsWith('<div class="wallkit-input wallkit-input--error', $html);
        $this->assertStringEndsWith('</div>', $html);

        // Когда есть ошибка, helpText не должен показываться
        $this->assertStringContainsString('<label', $html);
        $this->assertStringContainsString('<input', $html);
        $this->assertStringContainsString('wallkit-input__error', $html);
        $this->assertStringContainsString('wallkit-input--error', $html);
        $this->assertStringNotContainsString('wallkit-input__help', $html); // Help не должен быть!
        $this->assertStringContainsString('Error message', $html);
        $this->assertStringContainsString('⚠️', $html);

        // Проверяем порядок элементов
        $labelPos = strpos($html, 'wallkit-input__label');
        $inputPos = strpos($html, 'wallkit-input__field');
        $errorPos = strpos($html, 'wallkit-input__error');

        $this->assertLessThan($inputPos, $labelPos, 'Label should come before input');
        $this->assertLessThan($errorPos, $inputPos, 'Input should come before error');
    }

    public function testHtmlStructureWithoutHelpOrError(): void
    {
        // Тест без helpText и error
        $input = new Input(
            name: 'test',
            label: 'Test Label',
            id: 'test-id',
        // Без helpText и error
        );

        $html = (string)$input;

        // Должны быть только label и input
        $this->assertStringContainsString('<label', $html);
        $this->assertStringContainsString('<input', $html);
        $this->assertStringNotContainsString('wallkit-input__help', $html);
        $this->assertStringNotContainsString('wallkit-input__error', $html);
        $this->assertStringNotContainsString('wallkit-input--error', $html);
    }

    public function testDoesNotRenderEmptySections(): void
    {
        // Без label, helpText и error
        $input = new Input(name: 'minimal');

        $html = (string)$input;

        $this->assertStringNotContainsString('wallkit-input__label', $html);
        $this->assertStringNotContainsString('wallkit-input__help', $html);
        $this->assertStringNotContainsString('wallkit-input__error', $html);
        $this->assertStringNotContainsString('wallkit-input__required', $html);
        $this->assertStringNotContainsString('wallkit-input__toggle-password', $html);

        // Должен содержать только wrapper и input
        $this->assertStringContainsString('wallkit-input', $html);
        $this->assertStringContainsString('wallkit-input__wrapper', $html);
        $this->assertStringContainsString('wallkit-input__field', $html);
        $this->assertStringContainsString('type="text"', $html);
        $this->assertStringContainsString('name="minimal"', $html);
    }
}