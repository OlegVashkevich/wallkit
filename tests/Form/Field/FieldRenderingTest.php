<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Form\Field;

use OlegV\WallKit\Form\Field\Field;
use OlegV\WallKit\Form\Input\Input;
use PHPUnit\Framework\TestCase;

class FieldRenderingTest extends TestCase
{
    // ==================== ТЕСТЫ РЕНДЕРИНГА ====================

    public function testRendersBasicField(): void
    {
        $field = new Field(
            input: new Input(name: 'username', id: 'username-field'),
            label: 'Имя пользователя',
        );

        $html = (string)$field;

        $this->assertStringContainsString('wallkit-field', $html);
        $this->assertStringContainsString('wallkit-field__label', $html);
        $this->assertStringContainsString('Имя пользователя', $html);
        $this->assertStringContainsString('name="username"', $html);
    }

    public function testRendersFieldWithoutLabel(): void
    {
        $field = new Field(
            input: new Input(name: 'search'),
        );

        $html = (string)$field;

        $this->assertStringContainsString('wallkit-field', $html);
        $this->assertStringNotContainsString('wallkit-field__label', $html);
        $this->assertStringContainsString('wallkit-field__wrapper', $html);
        $this->assertStringContainsString('name="search"', $html);
    }

    public function testRendersFieldWithHelpText(): void
    {
        $field = new Field(
            input: new Input(name: 'phone'),
            label: 'Телефон',
            helpText: 'В формате +7 (XXX) XXX-XX-XX',
        );

        $html = (string)$field;

        $this->assertStringContainsString('wallkit-field__help', $html);
        $this->assertStringContainsString('В формате +7 (XXX) XXX-XX-XX', $html);
    }

    public function testRendersFieldWithError(): void
    {
        $field = new Field(
            input: new Input(name: 'email'),
            label: 'Email',
            error: 'Введите корректный email',
        );

        $html = (string)$field;

        $this->assertStringContainsString('wallkit-field--error', $html);
        $this->assertStringContainsString('wallkit-field__error', $html);
        $this->assertStringContainsString('Введите корректный email', $html);
        $this->assertStringContainsString('⚠️', $html);
        $this->assertStringNotContainsString('wallkit-field__help', $html);
    }

    public function testRendersFieldWithBothHelpAndError(): void
    {
        // Когда есть ошибка, helpText не должен показываться
        $field = new Field(
            input: new Input(name: 'password'),
            label: 'Пароль',
            helpText: 'Минимум 8 символов',
            error: 'Пароль слишком короткий',
        );

        $html = (string)$field;

        $this->assertStringContainsString('wallkit-field__error', $html);
        $this->assertStringContainsString('Пароль слишком короткий', $html);
        $this->assertStringNotContainsString('Минимум 8 символов', $html);
        $this->assertStringNotContainsString('wallkit-field__help', $html);
    }

    public function testRendersPasswordFieldWithToggle(): void
    {
        $field = new Field(
            input: new Input(name: 'password', type: 'password'),
            label: 'Пароль',
        );

        $html = (string)$field;

        $this->assertStringContainsString('type="password"', $html);
        $this->assertStringContainsString('wallkit-field__toggle-password', $html);
        $this->assertStringContainsString('Показать/скрыть пароль', $html);
    }

    public function testRendersPasswordFieldWithoutToggle(): void
    {
        $field = new Field(
            input: new Input(name: 'password', type: 'password'),
            label: 'Пароль',
            withPasswordToggle: false,
        );

        $html = (string)$field;

        $this->assertStringContainsString('type="password"', $html);
        $this->assertStringNotContainsString('wallkit-field__toggle-password', $html);
    }

    public function testRendersFieldWithoutLabelId(): void
    {
        // Когда input не имеет ID, label должен быть div вместо label[for]
        $field = new Field(
            input: new Input(name: 'comment'),
            label: 'Комментарий',
        );

        $html = (string)$field;

        $this->assertStringContainsString('<label class="wallkit-field__label"', $html);
        $this->assertStringNotContainsString('for="', $html);
        $this->assertStringContainsString('Комментарий', $html);
    }

    public function testRendersRequiredField(): void
    {
        $field = new Field(
            input: new Input(name: 'email', required: true, id: 'email-field'),
            label: 'Email',
        );

        $html = (string)$field;

        $this->assertStringContainsString('required', $html);
        $this->assertStringContainsString('wallkit-field__required', $html);
        $this->assertStringContainsString('*', $html);
    }

    // ==================== ТЕСТЫ БЕЗОПАСНОСТИ ====================

    public function testEscapesSpecialCharacters(): void
    {
        $field = new Field(
            input: new Input(name: 'xss'),
            label: '<script>alert("xss")</script>',
            helpText: '<b>bold</b> & "quotes"</div><script>alert(1)</script>',
        //error: '" onclick="alert(\'xss\')',
        );

        $html = (string)$field;

        // Проверяем экранирование
        $this->assertStringContainsString('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', $html);
        $this->assertStringContainsString(
            '&lt;b&gt;bold&lt;/b&gt; &amp; &quot;quotes&quot;&lt;/div&gt;&lt;script&gt;alert(1)&lt;/script&gt;',
            $html,
        );
        //$this->assertStringContainsString('&quot; onclick=&quot;alert(&apos;xss&apos;)&quot;', $html);
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringNotContainsString('<b>', $html);
        $this->assertStringNotContainsString('onclick=', $html);
    }

    public function testEscapesSpecialCharactersError(): void
    {
        $field = new Field(
            input: new Input(name: 'xss'),
            label: '<script>alert("xss")</script>',
            error: '" onclick="alert(\'xss\')',
        );

        $html = (string)$field;

        // Проверяем экранирование
        $this->assertStringContainsString('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', $html);
        $this->assertStringContainsString('&quot; onclick=&quot;alert(&apos;xss&apos;)', $html);
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringNotContainsString('<b>', $html);
    }

    // ==================== ТЕСТЫ HTML СТРУКТУРЫ ====================

    public function testHtmlStructureIsValid(): void
    {
        $field = new Field(
            input: new Input(name: 'test', id: 'test-id'),
            label: 'Test Label',
            helpText: 'Help text',
        );

        $html = (string)$field;

        // Проверяем базовую структуру
        $this->assertStringStartsWith('<div class="wallkit-field', $html);
        $this->assertStringEndsWith('</div>', $html);

        // Проверяем порядок элементов
        $labelPos = strpos($html, 'wallkit-field__label');
        $wrapperPos = strpos($html, 'wallkit-field__wrapper');
        $helpPos = strpos($html, 'wallkit-field__help');

        $this->assertLessThan($wrapperPos, $labelPos, 'Label should come before wrapper');
        $this->assertLessThan($helpPos, $wrapperPos, 'Wrapper should come before help');
    }
}