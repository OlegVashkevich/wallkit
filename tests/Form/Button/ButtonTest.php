<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Form\Button;

use OlegV\Exceptions\RenderException;
use OlegV\WallKit\Base\Base;
use OlegV\WallKit\Form\Button\Button;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Тесты для компонента Button
 */
class ButtonTest extends TestCase
{
    public string $cssFilePath = __DIR__ . '/../../../src/Form/Button/style.css';

    /**
     * Тест: Существование класса
     */
    public function testButtonClassExists(): void
    {
        $this->assertTrue(class_exists(Button::class));
    }

    /**
     * Тест: Класс является readonly
     */
    public function testButtonIsReadonly(): void
    {
        $reflection = new ReflectionClass(Button::class);
        $this->assertTrue($reflection->isReadOnly());
    }

    /**
     * Тест: Наследование от Base
     */
    public function testButtonExtendsBase(): void
    {
        $this->assertInstanceOf(Base::class, new Button('Test'));
    }

    /**
     * Тест: Создание кнопки с минимальными параметрами
     */
    public function testButtonCreationWithMinimalParams(): void
    {
        $button = new Button('Click me');

        $this->assertEquals('Click me', $button->text);
        $this->assertEquals('button', $button->type);
        $this->assertEquals('primary', $button->variant);
        $this->assertEquals('md', $button->size);
        $this->assertFalse($button->disabled);
        $this->assertNull($button->icon);
        $this->assertNull($button->iconAfter);
        $this->assertNull($button->href);
        $this->assertNull($button->target);
        $this->assertNull($button->id);
        $this->assertEmpty($button->classes);
        $this->assertEmpty($button->attributes);
        $this->assertNull($button->onClick);
        $this->assertFalse($button->fullWidth);
        $this->assertFalse($button->outline);
        $this->assertFalse($button->rounded);
    }

    /**
     * Тест: Создание кнопки со всеми параметрами
     */
    public function testButtonCreationWithAllParams(): void
    {
        $button = new Button(
            text: 'Save',
            type: 'submit',
            variant: 'success',
            size: 'lg',
            disabled: true,
            icon: '💾',
            iconAfter: '→',
            href: '/save',
            target: '_blank',
            id: 'save-btn',
            classes: ['custom-class'],
            attributes: ['data-test' => 'value'],
            onClick: 'saveForm()',
            fullWidth: true,
            outline: true,
            rounded: true,
        );

        $this->assertEquals('Save', $button->text);
        $this->assertEquals('submit', $button->type);
        $this->assertEquals('success', $button->variant);
        $this->assertEquals('lg', $button->size);
        $this->assertTrue($button->disabled);
        $this->assertEquals('💾', $button->icon);
        $this->assertEquals('→', $button->iconAfter);
        $this->assertEquals('/save', $button->href);
        $this->assertEquals('_blank', $button->target);
        $this->assertEquals('save-btn', $button->id);
        $this->assertEquals(['custom-class'], $button->classes);
        $this->assertEquals(['data-test' => 'value'], $button->attributes);
        $this->assertEquals('saveForm()', $button->onClick);
        $this->assertTrue($button->fullWidth);
        $this->assertTrue($button->outline);
        $this->assertTrue($button->rounded);
    }

    /**
     * Тест: Валидация типа кнопки
     */
    public function testButtonTypeValidation(): void
    {
        // Допустимые типы
        echo new Button('Test', type: 'button');
        echo new Button('Test', type: 'submit');
        echo new Button('Test', type: 'reset');

        // Недопустимый тип - тестируем через renderOriginal()
        $invalidButton = new Button('Test', type: 'invalid');

        $this->expectException(RenderException::class);
        $this->expectExceptionMessage('Неподдерживаемый тип кнопки: invalid');

        $invalidButton->renderOriginal();
    }

    /**
     * Тест: Валидация варианта стиля
     */
    public function testButtonVariantValidation(): void
    {
        // Допустимые варианты
        $validVariants = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link'];
        foreach ($validVariants as $variant) {
            echo new Button('Test', variant: $variant);
        }

        // Недопустимый вариант
        $invalidButton = new Button('Test', variant: 'invalid');
        $this->expectException(RenderException::class);
        $this->expectExceptionMessage('Неподдерживаемый вариант стиля: invalid');
        $invalidButton->renderOriginal();
    }

    /**
     * Тест: Валидация размера
     */
    public function testButtonSizeValidation(): void
    {
        // Допустимые размеры
        echo new Button('Test', size: 'sm');
        echo new Button('Test', size: 'md');
        echo new Button('Test', size: 'lg');

        // Недопустимый размер
        $invalidButton = new Button('Test', size: 'xl');
        $this->expectException(RenderException::class);
        $this->expectExceptionMessage('Неподдерживаемый размер: xl');
        $invalidButton->renderOriginal();
    }

    /**
     * Тест: Получение CSS классов
     */
    public function testGetButtonClasses(): void
    {
        $button = new Button('Test');
        $classes = $button->getButtonClasses();

        $this->assertContains('wallkit-button', $classes);
        $this->assertContains('wallkit-button--primary', $classes);
        $this->assertContains('wallkit-button--md', $classes);
    }

    /**
     * Тест: CSS классы для outline варианта
     */
    public function testGetButtonClassesWithOutline(): void
    {
        $button = new Button('Test', variant: 'primary', outline: true);
        $classes = $button->getButtonClasses();

        $this->assertContains('wallkit-button--outline-primary', $classes);
        $this->assertNotContains('wallkit-button--primary', $classes);
    }

    /**
     * Тест: CSS классы для disabled состояния
     */
    public function testGetButtonClassesWithDisabled(): void
    {
        $button = new Button('Test', disabled: true);
        $classes = $button->getButtonClasses();

        $this->assertContains('wallkit-button--disabled', $classes);
    }

    /**
     * Тест: CSS классы для fullWidth
     */
    public function testGetButtonClassesWithFullWidth(): void
    {
        $button = new Button('Test', fullWidth: true);
        $classes = $button->getButtonClasses();

        $this->assertContains('wallkit-button--full-width', $classes);
    }

    /**
     * Тест: CSS классы для rounded
     */
    public function testGetButtonClassesWithRounded(): void
    {
        $button = new Button('Test', rounded: true);
        $classes = $button->getButtonClasses();

        $this->assertContains('wallkit-button--rounded', $classes);
    }

    /**
     * Тест: Получение HTML атрибутов для обычной кнопки
     */
    public function testGetButtonAttributesForRegularButton(): void
    {
        $button = new Button(
            'Test',
            disabled: true,
            id: 'test-btn',
            onClick: 'test()',
        );

        $attributes = $button->getButtonAttributes();

        $this->assertEquals('test-btn', $attributes['id']);
        $this->assertEquals('button', $attributes['type']);
        $this->assertTrue($attributes['disabled']);
        $this->assertEquals('test()', $attributes['onclick']);
        $this->assertStringContainsString('wallkit-button', (string) $attributes['class']);
    }

    /**
     * Тест: Получение HTML атрибутов для кнопки-ссылки
     */
    public function testGetButtonAttributesForLinkButton(): void
    {
        $button = new Button(
            'Test',
            href: '/test',
            target: '_blank',
        );

        $attributes = $button->getButtonAttributes();

        $this->assertEquals('/test', $attributes['href']);
        $this->assertEquals('_blank', $attributes['target']);
        $this->assertArrayNotHasKey('type', $attributes);
        $this->assertArrayNotHasKey('disabled', $attributes);
    }

    /**
     * Тест: Проверка isLink для обычной кнопки
     */
    public function testIsLinkForRegularButton(): void
    {
        $button = new Button('Test');
        $this->assertFalse($button->isLink());
    }

    /**
     * Тест: Проверка isLink для кнопки-ссылки
     */
    public function testIsLinkForLinkButton(): void
    {
        $button = new Button('Test', href: '/test');
        $this->assertTrue($button->isLink());
    }

    /**
     * Тест: Существование CSS файла стилей
     */
    public function testCssFileExists(): void
    {
        $this->assertFileExists($this->cssFilePath, 'CSS файл стилей кнопки должен существовать');
    }

    /**
     * Тест: Проверка CSS переменных в файле стилей
     */
    public function testCssVariablesAreDefined(): void
    {
        $cssContent = file_get_contents($this->cssFilePath);

        // Проверяем основные CSS переменные для кнопок
        $expectedVariables = [
            // Основные параметры кнопки
            '--wk-btn-padding',
            '--wk-btn-gap',
            '--wk-btn-radius',
            '--wk-btn-border',

            // Типографика
            '--wk-btn-font-family',
            '--wk-btn-font-size',
            '--wk-btn-font-weight',
            '--wk-btn-line-height',

            // Цвета
            '--wk-btn-bg',
            '--wk-btn-text',
            '--wk-btn-border-color',

            // Состояния
            '--wk-btn-hover-transform',
            '--wk-btn-hover-shadow',
            '--wk-btn-focus-shadow',
            '--wk-btn-disabled-opacity',

            // Анимации
            '--wk-btn-transition',
        ];

        foreach ($expectedVariables as $variable) {
            $this->assertStringContainsString(
                $variable . ':',
                $cssContent,
                "CSS переменная $variable должна быть определена",
            );
        }
    }

    /**
     * Тест: Проверка классов стилей в CSS файле
     */
    public function testCssClassesAreDefined(): void
    {
        $cssContent = file_get_contents($this->cssFilePath);

        // Проверяем основные CSS классы
        $expectedClasses = [
            // Базовый класс
            '.wallkit-button',

            // Варианты
            '.wallkit-button--primary',
            '.wallkit-button--secondary',
            '.wallkit-button--success',
            '.wallkit-button--danger',
            '.wallkit-button--warning',
            '.wallkit-button--info',
            '.wallkit-button--light',
            '.wallkit-button--dark',
            '.wallkit-button--link',

            // Outline варианты
            '.wallkit-button--outline-primary',
            '.wallkit-button--outline-secondary',
            '.wallkit-button--outline-success',
            '.wallkit-button--outline-danger',

            // Размеры
            '.wallkit-button--sm',
            '.wallkit-button--md',
            '.wallkit-button--lg',

            // Модификаторы
            '.wallkit-button--full-width',
            '.wallkit-button--rounded',
            '.wallkit-button--disabled',

            // Состояния
            '.wallkit-button:focus',

            // Иконки
            '.wallkit-button__icon',
            '.wallkit-button__icon--after',
        ];

        foreach ($expectedClasses as $class) {
            $this->assertStringContainsString(
                $class,
                $cssContent,
                "CSS класс $class должен быть определен",
            );
        }
    }

    /**
     * Тест: Проверка hover состояний для outline кнопок
     */
    public function testCssOutlineHoverStates(): void
    {
        $cssContent = file_get_contents($this->cssFilePath);

        $expectedHoverStates = [
            '.wallkit-button--outline-primary:hover',
            '.wallkit-button--outline-secondary:hover',
            '.wallkit-button--outline-success:hover',
            '.wallkit-button--outline-danger:hover',
        ];

        foreach ($expectedHoverStates as $state) {
            $this->assertStringContainsString(
                $state,
                $cssContent,
                "Hover состояние $state должно быть определено",
            );
        }
    }

    /**
     * Тест: Проверка CSS-переменных на корректные значения
     */
    public function testCssVariablesHaveValidValues(): void
    {
        $cssContent = file_get_contents($this->cssFilePath);

        // Проверяем, что ключевые переменные имеют валидные значения
        $lines = explode("\n", $cssContent);
        foreach ($lines as $line) {
            $trimmedLine = trim($line);

            if (str_contains($trimmedLine, '--wk-btn-font-family:')) {
                $this->assertStringContainsString(
                    'var(--wk-font-family)',
                    $trimmedLine,
                    'Шрифт кнопки должен использовать переменную font-family',
                );
            }

            if (str_contains($trimmedLine, '--wk-btn-transition:')) {
                $this->assertStringContainsString(
                    'var(--wk-transition)',
                    $trimmedLine,
                    'Анимация кнопки должна использовать переменную transition',
                );
            }

            if (str_contains($trimmedLine, '--wk-btn-disabled-opacity:')) {
                $this->assertMatchesRegularExpression(
                    '/--wk-btn-disabled-opacity:\s*0\.6/',
                    $trimmedLine,
                    'Непрозрачность disabled кнопки должна быть 0.6',
                );
            }
        }
    }

    /**
     * Тест: Проверка специфических стилей для link варианта
     */
    public function testCssLinkVariantStyles(): void
    {
        $cssContent = file_get_contents($this->cssFilePath);

        // Проверяем специфические стили для кнопки-ссылки
        $this->assertStringContainsString(
            '.wallkit-button--link',
            $cssContent,
            'Стили для link варианта должны быть определены',
        );

        $this->assertStringContainsString(
            '--wk-btn-bg: transparent',
            $cssContent,
            'Link кнопка должна иметь прозрачный фон',
        );

        $this->assertStringContainsString(
            '--wk-btn-text: var(--wk-color-primary)',
            $cssContent,
            'Link кнопка должна использовать primary цвет для текста',
        );

        $this->assertStringContainsString(
            'text-decoration: underline',
            $cssContent,
            'Link кнопка должна иметь подчеркивание',
        );
    }

    /**
     * Тест: Проверка rounded модификатора
     */
    public function testCssRoundedModifier(): void
    {
        $cssContent = file_get_contents($this->cssFilePath);

        $this->assertStringContainsString(
            '.wallkit-button--rounded',
            $cssContent,
            'Стили для rounded модификатора должны быть определены',
        );

        $this->assertStringContainsString(
            '--wk-btn-radius: 100%',
            $cssContent,
            'Rounded кнопка должна иметь большой радиус для круглой формы',
        );
    }

    /**
     * Тест: Проверка disabled состояний
     */
    public function testCssDisabledStates(): void
    {
        $cssContent = file_get_contents($this->cssFilePath);

        // Проверяем стили для disabled состояния
        $this->assertStringContainsString(
            '.wallkit-button--disabled',
            $cssContent,
            'Стили для disabled класса должны быть определены',
        );

        $this->assertStringContainsString(
            '.wallkit-button[disabled]',
            $cssContent,
            'Стили для disabled атрибута должны быть определены',
        );

        $this->assertStringContainsString(
            'cursor: not-allowed',
            $cssContent,
            'Disabled кнопка должна иметь курсор not-allowed',
        );
    }

    /**
     * Тест: Проверка hover и focus состояний
     */
    public function testCssHoverAndFocusStates(): void
    {
        $cssContent = file_get_contents($this->cssFilePath);

        // Проверяем hover состояние (исключая disabled)
        $this->assertStringContainsString(
            '.wallkit-button:not(.wallkit-button--disabled):not([disabled]):hover',
            $cssContent,
            'Hover состояние должно исключать disabled кнопки',
        );

        $this->assertStringContainsString(
            'transform: var(--wk-btn-hover-transform)',
            $cssContent,
            'Hover должен включать трансформацию',
        );

        $this->assertStringContainsString(
            'box-shadow: var(--wk-btn-hover-shadow)',
            $cssContent,
            'Hover должен включать тень',
        );

        // Проверяем focus состояние
        $this->assertStringContainsString(
            '.wallkit-button:focus',
            $cssContent,
            'Focus состояние должно быть определено',
        );

        $this->assertStringContainsString(
            'outline: none',
            $cssContent,
            'Focus должен скрывать стандартный outline',
        );

        $this->assertStringContainsString(
            'box-shadow: var(--wk-btn-focus-shadow)',
            $cssContent,
            'Focus должен использовать outline тень',
        );
    }

    /**
     * Тест: Рендеринг обычной кнопки через __toString()
     */
    public function testButtonRenderingViaToString(): void
    {
        $button = new Button('Click me');
        $html = (string) $button;

        $this->assertStringContainsString('<button', $html);
        $this->assertStringContainsString('type="button"', $html);
        $this->assertStringContainsString('Click me', $html);
        $this->assertStringContainsString('wallkit-button', $html);
        $this->assertStringContainsString('wallkit-button__text', $html);
        $this->assertStringNotContainsString('wallkit-button__icon', $html);
    }

    /**
     * Тест: Рендеринг кнопки-ссылки
     */
    public function testLinkButtonRendering(): void
    {
        $button = new Button(
            text: 'Go to site',
            href: '/home',
            target: '_blank',
        );

        $html = (string) $button;

        $this->assertStringContainsString('<a', $html);
        $this->assertStringContainsString('href="/home"', $html);
        $this->assertStringContainsString('target="_blank"', $html);
        $this->assertStringNotContainsString('type="', $html);
        $this->assertStringContainsString('Go to site', $html);
    }

    /**
     * Тест: Рендеринг кнопки с иконкой до текста
     */
    public function testButtonWithIconBefore(): void
    {
        $button = new Button(
            text: 'Save',
            icon: '💾',
        );

        $html = (string) $button;

        $this->assertStringContainsString('<span class="wallkit-button__icon">💾</span>', $html);
        $this->assertStringContainsString('<span class="wallkit-button__text">Save</span>', $html);
        $this->assertStringNotContainsString('wallkit-button__icon--after', $html);
    }

    /**
     * Тест: Рендеринг кнопки с иконкой после текста
     */
    public function testButtonWithIconAfter(): void
    {
        $button = new Button(
            text: 'Next',
            iconAfter: '→',
        );

        $html = (string) $button;

        $this->assertStringContainsString('<span class="wallkit-button__text">Next</span>', $html);
        $this->assertStringContainsString(
            '<span class="wallkit-button__icon wallkit-button__icon--after">→</span>',
            $html,
        );
    }

    /**
     * Тест: Рендеринг кнопки с обеими иконками
     */
    public function testButtonWithBothIcons(): void
    {
        $button = new Button(
            text: 'Download',
            icon: '⬇️',
            iconAfter: '📥',
        );

        $html = (string) $button;

        $this->assertStringContainsString('<span class="wallkit-button__icon">⬇️</span>', $html);
        $this->assertStringContainsString('<span class="wallkit-button__text">Download</span>', $html);
        $this->assertStringContainsString(
            '<span class="wallkit-button__icon wallkit-button__icon--after">📥</span>',
            $html,
        );
    }

    /**
     * Тест: Рендеринг disabled кнопки
     */
    public function testDisabledButtonRendering(): void
    {
        $button = new Button(
            text: 'Disabled',
            disabled: true,
        );

        $html = (string) $button;

        $this->assertStringContainsString('disabled', $html);
        $this->assertStringContainsString('wallkit-button--disabled', $html);
    }

    /**
     * Тест: Рендеринг кнопки с onClick
     */
    public function testButtonWithOnClick(): void
    {
        $button = new Button(
            text: 'Click me',
            onClick: 'alert("test")',
        );

        $html = (string) $button;

        $this->assertStringNotContainsString('onclick', $html);
    }

    /**
     * Тест: Рендеринг submit кнопки
     */
    public function testSubmitButtonRendering(): void
    {
        $button = new Button(
            text: 'Submit',
            type: 'submit',
        );

        $html = (string) $button;

        $this->assertStringContainsString('type="submit"', $html);
    }

    /**
     * Тест: Рендеринг reset кнопки
     */
    public function testResetButtonRendering(): void
    {
        $button = new Button(
            text: 'Reset',
            type: 'reset',
        );

        $html = (string) $button;

        $this->assertStringContainsString('type="reset"', $html);
    }

    /**
     * Тест: Рендеринг outline кнопки
     */
    public function testOutlineButtonRendering(): void
    {
        $button = new Button(
            text: 'Outline',
            variant: 'primary',
            outline: true,
        );

        $html = (string) $button;

        $this->assertStringContainsString('wallkit-button--outline-primary', $html);
        $this->assertStringNotContainsString('wallkit-button--primary', $html);
    }

    /**
     * Тест: Рендеринг full-width кнопки
     */
    public function testFullWidthButtonRendering(): void
    {
        $button = new Button(
            text: 'Full Width',
            fullWidth: true,
        );

        $html = (string) $button;

        $this->assertStringContainsString('wallkit-button--full-width', $html);
    }

    /**
     * Тест: Рендеринг rounded кнопки
     */
    public function testRoundedButtonRendering(): void
    {
        $button = new Button(
            text: 'Rounded',
            rounded: true,
        );

        $html = (string) $button;

        $this->assertStringContainsString('wallkit-button--rounded', $html);
    }

    /**
     * Тест: Рендеринг кнопки разных размеров
     */
    public function testButtonSizesRendering(): void
    {
        $sizes = ['sm', 'md', 'lg'];

        foreach ($sizes as $size) {
            $button = new Button(
                text: "Size $size",
                size: $size,
            );

            $html = (string) $button;
            $this->assertStringContainsString("wallkit-button--$size", $html);
        }
    }

    /**
     * Тест: Рендеринг кнопки разных вариантов
     */
    public function testButtonVariantsRendering(): void
    {
        $variants = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link'];

        foreach ($variants as $variant) {
            $button = new Button(
                text: ucfirst($variant),
                variant: $variant,
            );

            $html = (string) $button;
            $this->assertStringContainsString("wallkit-button--$variant", $html);
        }
    }

    /**
     * Тест: Рендеринг кнопки с кастомными классами
     */
    public function testButtonWithCustomClasses(): void
    {
        $button = new Button(
            text: 'Custom',
            classes: ['custom-class', 'another-class'],
        );

        $html = (string) $button;

        $this->assertStringContainsString('custom-class', $html);
        $this->assertStringContainsString('another-class', $html);
    }

    /**
     * Тест: Рендеринг кнопки с кастомными атрибутами
     */
    public function testButtonWithCustomAttributes(): void
    {
        $button = new Button(
            text: 'Custom Attr',
            attributes: [
                'data-test' => 'value',
                'aria-label' => 'Test button',
                'title' => 'Tooltip',
            ],
        );

        $html = (string) $button;

        $this->assertStringContainsString('data-test="value"', $html);
        $this->assertStringContainsString('aria-label="Test button"', $html);
        $this->assertStringContainsString('title="Tooltip"', $html);
    }

    /**
     * Тест: Рендеринг кнопки с ID
     */
    public function testButtonWithId(): void
    {
        $button = new Button(
            text: 'With ID',
            id: 'test-button-id',
        );

        $html = (string) $button;

        $this->assertStringContainsString('id="test-button-id"', $html);
    }

    /**
     * Тест: Проверка, что null-значения не рендерятся в HTML
     */
    public function testNullValuesNotRendered(): void
    {
        $button = new Button(
            text: 'Test',
            href: null,
            target: null,
            id: null,
            onClick: null,
        );

        $html = (string) $button;

        $this->assertStringNotContainsString('id="', $html);
        $this->assertStringNotContainsString('onclick="', $html);
        $this->assertStringNotContainsString('href="', $html);
        $this->assertStringNotContainsString('target="', $html);
    }

    /**
     * Тест: Проверка корректного HTML вывода через echo
     */
    public function testButtonEchoOutput(): void
    {
        ob_start();
        echo new Button('Echo Test');
        $html = ob_get_clean();

        $this->assertStringContainsString('<button', $html);
        $this->assertStringContainsString('Echo Test', $html);
    }
}
