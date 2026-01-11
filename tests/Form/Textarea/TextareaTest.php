<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Form\Textarea;

use OlegV\Exceptions\RenderException;
use OlegV\WallKit\Base\Base;
use OlegV\WallKit\Form\Textarea\Textarea;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Тест компонента Textarea
 */
class TextareaTest extends TestCase
{
    public function testTextareaClassExists(): void
    {
        $this->assertTrue(class_exists(Textarea::class));
    }

    public function testTextareaIsReadonly(): void
    {
        $reflection = new ReflectionClass(Textarea::class);
        $this->assertTrue($reflection->isReadOnly());
    }

    public function testTextareaExtendsBase(): void
    {
        $textarea = new Textarea('test-name');
        $this->assertInstanceOf(Base::class, $textarea);
    }

    public function testTextareaRendersWithRequiredName(): void
    {
        $textarea = new Textarea('comment');
        $this->assertStringContainsString('name="comment"', (string)$textarea);
    }

    public function testTextareaRendersWithPlaceholder(): void
    {
        $textarea = new Textarea('comment', 'Введите текст');
        $output = (string)$textarea;
        $this->assertStringContainsString('placeholder="Введите текст"', $output);
    }

    public function testTextareaRendersWithValue(): void
    {
        $textarea = new Textarea('comment', value: 'Текущий текст');
        $output = (string)$textarea;
        $this->assertStringContainsString('>Текущий текст<', $output);
    }

    public function testTextareaRendersWithRows(): void
    {
        $textarea = new Textarea('comment', rows: 5);
        $output = (string)$textarea;
        $this->assertStringContainsString('rows="5"', $output);
    }

    public function testTextareaRendersWithMaxLength(): void
    {
        $textarea = new Textarea('comment', maxLength: 500);
        $output = (string)$textarea;
        $this->assertStringContainsString('maxlength="500"', $output);
    }

    public function testTextareaRendersAsRequired(): void
    {
        $textarea = new Textarea('comment', required: true);
        $output = (string)$textarea;
        $this->assertStringContainsString('required', $output);
    }

    public function testTextareaRendersAsDisabled(): void
    {
        $textarea = new Textarea('comment', disabled: true);
        $output = (string)$textarea;
        $this->assertStringContainsString('disabled', $output);
    }

    public function testTextareaRendersAsReadonly(): void
    {
        $textarea = new Textarea('comment', readonly: true);
        $output = (string)$textarea;
        $this->assertStringContainsString('readonly', $output);
    }

    public function testTextareaRendersWithId(): void
    {
        $textarea = new Textarea('comment', id: 'comment-field');
        $output = (string)$textarea;
        $this->assertStringContainsString('id="comment-field"', $output);
    }

    public function testTextareaRendersWithAutoFocus(): void
    {
        $textarea = new Textarea('comment', autoFocus: true);
        $output = (string)$textarea;
        $this->assertStringContainsString('autofocus', $output);
    }

    public function testTextareaRendersWithAutocomplete(): void
    {
        $textarea = new Textarea('comment', autocomplete: 'on');
        $output = (string)$textarea;
        $this->assertStringContainsString('autocomplete="on"', $output);
    }

    public function testTextareaRendersWithSpellcheckEnabled(): void
    {
        $textarea = new Textarea('comment', spellcheck: true);
        $output = (string)$textarea;
        $this->assertStringContainsString('spellcheck="true"', $output);
    }

    public function testTextareaRendersWithSpellcheckDisabled(): void
    {
        $textarea = new Textarea('comment', spellcheck: false);
        $output = (string)$textarea;
        $this->assertStringContainsString('spellcheck="false"', $output);
    }

    public function testTextareaRendersWithAdditionalClasses(): void
    {
        $textarea = new Textarea('comment', classes: ['custom-class', 'another-class']);
        $output = (string)$textarea;
        $this->assertStringContainsString('class="wallkit-textarea__field custom-class another-class"', $output);
    }

    public function testTextareaRendersWithAdditionalAttributes(): void
    {
        $textarea = new Textarea('comment', attributes: [
            'data-test' => 'value',
            'aria-label' => 'Комментарий',
        ]);
        $output = (string)$textarea;
        $this->assertStringContainsString('data-test="value"', $output);
        $this->assertStringContainsString('aria-label="Комментарий"', $output);
    }

    public function testTextareaUsesCorrectCssClass(): void
    {
        $textarea = new Textarea('test');
        $output = (string)$textarea;
        $this->assertStringContainsString('class="wallkit-textarea__field"', $output);
    }

    public function testTextareaEmptyNameThrowsException(): void
    {
        $this->expectException(RenderException::class);
        $this->expectExceptionMessage('Имя поля обязательно');

        $textarea = new Textarea('');
        $textarea->renderOriginal();
    }

    public function testTextareaWhitespaceNameThrowsException(): void
    {
        $this->expectException(RenderException::class);
        $this->expectExceptionMessage('Имя поля обязательно');

        $textarea = new Textarea('   ');
        $textarea->renderOriginal();
    }

    public function testTextareaValidNameDoesNotThrowException(): void
    {
        $textarea = new Textarea('valid-name');
        try {
            $output = (string)$textarea;
            $this->assertStringContainsString('name="valid-name"', $output);
        } catch (RenderException) {
            $this->fail('Не должно выбрасывать исключение для валидного имени');
        }
    }

    public function testTextareaDefaultRowsIsFour(): void
    {
        $textarea = new Textarea('test');
        $this->assertEquals(4, $textarea->rows);
    }

    public function testTextareaDefaultRequiredIsFalse(): void
    {
        $textarea = new Textarea('test');
        $this->assertFalse($textarea->required);
    }

    public function testTextareaDefaultDisabledIsFalse(): void
    {
        $textarea = new Textarea('test');
        $this->assertFalse($textarea->disabled);
    }

    public function testTextareaDefaultReadonlyIsFalse(): void
    {
        $textarea = new Textarea('test');
        $this->assertFalse($textarea->readonly);
    }

    public function testTextareaDefaultAutoFocusIsFalse(): void
    {
        $textarea = new Textarea('test');
        $this->assertFalse($textarea->autoFocus);
    }

    public function testTextareaEmptyValueIsNull(): void
    {
        $textarea = new Textarea('test');
        $this->assertNull($textarea->value);
    }

    public function testTextareaEmptyPlaceholderIsNull(): void
    {
        $textarea = new Textarea('test');
        $this->assertNull($textarea->placeholder);
    }

    public function testTextareaEmptyMaxLengthIsNull(): void
    {
        $textarea = new Textarea('test');
        $this->assertNull($textarea->maxLength);
    }

    public function testTextareaEmptyIdIsNull(): void
    {
        $textarea = new Textarea('test');
        $this->assertNull($textarea->id);
    }

    public function testTextareaEmptyAutocompleteIsNull(): void
    {
        $textarea = new Textarea('test');
        $this->assertNull($textarea->autocomplete);
    }

    public function testTextareaEmptySpellcheckIsNull(): void
    {
        $textarea = new Textarea('test');
        $this->assertNull($textarea->spellcheck);
    }

    public function testTextareaDefaultClassesIsEmptyArray(): void
    {
        $textarea = new Textarea('test');
        $this->assertIsArray($textarea->classes);
        $this->assertEmpty($textarea->classes);
    }

    public function testTextareaDefaultAttributesIsEmptyArray(): void
    {
        $textarea = new Textarea('test');
        $this->assertIsArray($textarea->attributes);
        $this->assertEmpty($textarea->attributes);
    }

    public function testTextareaValueIsEscaped(): void
    {
        $textarea = new Textarea('test', value: '<script>alert("xss")</script>');
        $output = (string)$textarea;
        $this->assertStringContainsString('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', $output);
        $this->assertStringNotContainsString('<script>', $output);
    }

    public function testTextareaPlaceholderIsEscaped(): void
    {
        $textarea = new Textarea('test', placeholder: '"test" & "demo"');
        $output = (string)$textarea;
        $this->assertStringContainsString('placeholder="&quot;test&quot; &amp; &quot;demo&quot;"', $output);
    }

    public function testTextareaAttributesAreEscaped(): void
    {
        $textarea = new Textarea('test', attributes: [
            'data-html' => '<div>test</div>',
            'onclick' => 'alert("xss")',
        ]);
        $output = (string)$textarea;
        echo $output;
        $this->assertStringContainsString('data-html="&lt;div&gt;test&lt;/div&gt;"', $output);
        $this->assertStringNotContainsString('onclick="alert"', $output);
    }

    public function testTextareaGetTextareaClassesReturnsDefaultClass(): void
    {
        $textarea = new Textarea('test');
        $classes = $textarea->getTextareaClasses();
        $this->assertContains('wallkit-textarea__field', $classes);
        $this->assertCount(1, $classes);
    }

    public function testTextareaGetTextareaClassesIncludesAdditionalClasses(): void
    {
        $textarea = new Textarea('test', classes: ['class1', 'class2']);
        $classes = $textarea->getTextareaClasses();
        $this->assertContains('wallkit-textarea__field', $classes);
        $this->assertContains('class1', $classes);
        $this->assertContains('class2', $classes);
        $this->assertCount(3, $classes);
    }

    public function testTextareaGetTextareaAttributesIncludesAllProperties(): void
    {
        $textarea = new Textarea(
            'comment',
            placeholder: 'Введите текст',
            value: 'Текст',
            rows: 5,
            maxLength: 100,
            required: true,
            id: 'my-textarea',
        );

        $attributes = $textarea->getTextareaAttributes();

        $this->assertEquals('my-textarea', $attributes['id']);
        $this->assertEquals('comment', $attributes['name']);
        $this->assertEquals('Введите текст', $attributes['placeholder']);
        $this->assertEquals(5, $attributes['rows']);
        $this->assertEquals(100, $attributes['maxlength']);
        $this->assertTrue($attributes['required']);
        $this->assertStringContainsString('wallkit-textarea__field', (string)$attributes['class']);
    }

    public function testTextareaGetTextareaAttributesFiltersNullValues(): void
    {
        $textarea = new Textarea('test');
        $attributes = $textarea->getTextareaAttributes();

        $this->assertArrayNotHasKey('placeholder', $attributes);
        $this->assertArrayNotHasKey('maxlength', $attributes);
        $this->assertArrayNotHasKey('autocomplete', $attributes);
        $this->assertArrayNotHasKey('spellcheck', $attributes);
    }

    public function testTextareaCompleteRenderingOutput(): void
    {
        $textarea = new Textarea(
            'user_bio',
            placeholder: 'Расскажите о себе...',
            value: 'Я разработчик из Москвы.',
            rows: 5,
            maxLength: 500,
            required: true,
            id: 'bio-field',
            classes: ['bio-textarea'],
            autoFocus: true,
            spellcheck: true,
        );

        $output = (string)$textarea;

        // Проверяем наличие всех ключевых атрибутов
        $this->assertStringContainsString('<textarea', $output);
        $this->assertStringContainsString('name="user_bio"', $output);
        $this->assertStringContainsString('id="bio-field"', $output);
        $this->assertStringContainsString('placeholder="Расскажите о себе..."', $output);
        $this->assertStringContainsString('rows="5"', $output);
        $this->assertStringContainsString('maxlength="500"', $output);
        $this->assertStringContainsString('required', $output);
        $this->assertStringContainsString('autofocus', $output);
        $this->assertStringContainsString('spellcheck="true"', $output);
        $this->assertStringContainsString('class="wallkit-textarea__field bio-textarea"', $output);
        $this->assertStringContainsString('>Я разработчик из Москвы.<', $output);
        $this->assertStringContainsString('</textarea>', $output);
    }

    public function testTextareaWithAllOptionalParametersNull(): void
    {
        $textarea = new Textarea('simple');
        $output = (string)$textarea;

        // Проверяем минимальный набор атрибутов
        $this->assertStringContainsString('name="simple"', $output);
        $this->assertStringContainsString('rows="4"', $output);
        $this->assertStringContainsString('class="wallkit-textarea__field"', $output);

        // Проверяем отсутствие необязательных атрибутов
        $this->assertStringNotContainsString('placeholder="', $output);
        $this->assertStringNotContainsString('maxlength="', $output);
        $this->assertStringNotContainsString('required', $output);
        $this->assertStringNotContainsString('disabled', $output);
        $this->assertStringNotContainsString('readonly', $output);
        $this->assertStringNotContainsString('autofocus', $output);
        $this->assertStringNotContainsString('spellcheck="', $output);
    }
}