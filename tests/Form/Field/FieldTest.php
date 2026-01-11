<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Form\Field;

use OlegV\Exceptions\RenderException;
use OlegV\WallKit\Base\Base;
use OlegV\WallKit\Form\Field\Field;
use OlegV\WallKit\Form\Input\Input;
use OlegV\WallKit\Form\Select\Select;
use OlegV\WallKit\Form\Textarea\Textarea;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Тесты для компонента Field
 */
class FieldTest extends TestCase
{
    /**
     * Тест существования класса Field
     */
    public function testFieldClassExists(): void
    {
        $this->assertTrue(class_exists(Field::class));
    }

    /**
     * Тест, что Field является readonly классом
     */
    public function testFieldIsReadonly(): void
    {
        $reflection = new ReflectionClass(Field::class);
        $this->assertTrue($reflection->isReadOnly());
    }

    /**
     * Тест, что Field наследуется от Base
     */
    public function testFieldExtendsBase(): void
    {
        $input = new Input(name: 'test');
        $field = new Field(input: $input);
        $this->assertInstanceOf(Base::class, $field);
    }

    /**
     * Тест создания Field с Input
     */
    public function testFieldCreationWithInput(): void
    {
        $input = new Input(name: 'username');
        $field = new Field(
            input: $input,
            label: 'Имя пользователя',
            helpText: 'Введите ваше имя',
            error: null,
            withPasswordToggle: false,
        );

        $this->assertInstanceOf(Field::class, $field);
        $this->assertInstanceOf(Input::class, $field->input);
        $this->assertEquals('Имя пользователя', $field->label);
        $this->assertEquals('Введите ваше имя', $field->helpText);
        $this->assertNull($field->error);
        $this->assertFalse($field->withPasswordToggle);
    }

    /**
     * Тест создания Field с Textarea
     */
    public function testFieldCreationWithTextarea(): void
    {
        $textarea = new Textarea(name: 'description');
        $field = new Field(
            input: $textarea,
            label: 'Описание',
            error: 'Поле обязательно для заполнения',
        );

        $this->assertInstanceOf(Textarea::class, $field->input);
        $this->assertEquals('Описание', $field->label);
        $this->assertEquals('Поле обязательно для заполнения', $field->error);
        $this->assertTrue($field->withPasswordToggle); // По умолчанию true
    }

    /**
     * Тест создания Field с Select
     */
    public function testFieldCreationWithSelect(): void
    {
        $select = new Select(name: 'country');
        $field = new Field(
            input: $select,
            label: 'Страна',
        );

        $this->assertInstanceOf(Select::class, $field->input);
        $this->assertEquals('Страна', $field->label);
    }

    /**
     * Тест метода getWrapperClasses без ошибки
     */
    public function testGetWrapperClassesWithoutError(): void
    {
        $input = new Input(name: 'test');
        $field = new Field(
            input: $input,
            wrapperClasses: ['custom-class'],
        );

        $classes = $field->getWrapperClasses();
        $this->assertContains('wallkit-field', $classes);
        $this->assertNotContains('wallkit-field--error', $classes);
        $this->assertNotContains('wallkit-field--disabled', $classes);
        $this->assertContains('custom-class', $classes);
    }

    /**
     * Тест метода getWrapperClasses с ошибкой
     */
    public function testGetWrapperClassesWithError(): void
    {
        $input = new Input(name: 'test');
        $field = new Field(
            input: $input,
            error: 'Ошибка валидации',
        );

        $classes = $field->getWrapperClasses();
        $this->assertContains('wallkit-field', $classes);
        $this->assertContains('wallkit-field--error', $classes);
    }

    /**
     * Тест метода getWrapperClasses с отключенным полем
     */
    public function testGetWrapperClassesWithDisabledInput(): void
    {
        $input = new Input(name: 'test', disabled: true);
        $field = new Field(input: $input);

        $classes = $field->getWrapperClasses();
        $this->assertContains('wallkit-field', $classes);
        $this->assertContains('wallkit-field--disabled', $classes);
    }

    /**
     * Тест метода getLabelId
     */
    public function testGetLabelId(): void
    {
        // С полем, у которого есть ID
        $inputWithId = new Input(name: 'test', id: 'test-id');
        $fieldWithId = new Field(input: $inputWithId);
        $this->assertEquals('test-id', $fieldWithId->getLabelId());

        // С полем без ID
        $inputWithoutId = new Input(name: 'test');
        $fieldWithoutId = new Field(input: $inputWithoutId);
        $this->assertNull($fieldWithoutId->getLabelId());
    }

    /**
     * Тест метода shouldShowPasswordToggle для поля password
     */
    public function testShouldShowPasswordToggleForPassword(): void
    {
        $passwordInput = new Input(name: 'password', type: 'password');
        $field = new Field(
            input: $passwordInput,
            withPasswordToggle: true,
        );

        $this->assertTrue($field->shouldShowPasswordToggle());
    }

    /**
     * Тест метода shouldShowPasswordToggle для поля password с отключенным toggle
     */
    public function testShouldShowPasswordToggleDisabled(): void
    {
        $passwordInput = new Input(name: 'password', type: 'password');
        $field = new Field(
            input: $passwordInput,
            withPasswordToggle: false,
        );

        $this->assertFalse($field->shouldShowPasswordToggle());
    }

    /**
     * Тест метода shouldShowPasswordToggle для не-password поля
     */
    public function testShouldShowPasswordToggleForNonPassword(): void
    {
        $emailInput = new Input(name: 'email', type: 'email');
        $field = new Field(input: $emailInput);

        $this->assertFalse($field->shouldShowPasswordToggle());
    }

    /**
     * Тест метода getFieldType
     */
    public function testGetFieldType(): void
    {
        // Для Input
        $textInput = new Input(name: 'text', type: 'text');
        $field1 = new Field(input: $textInput);
        $this->assertEquals('text', $field1->getFieldType());

        $emailInput = new Input(name: 'email', type: 'email');
        $field2 = new Field(input: $emailInput);
        $this->assertEquals('email', $field2->getFieldType());

        // Для Textarea
        $textarea = new Textarea(name: 'bio');
        $field3 = new Field(input: $textarea);
        $this->assertEquals('textarea', $field3->getFieldType());

        // Для Select
        $select = new Select(name: 'country');
        $field4 = new Field(input: $select);
        $this->assertEquals('select', $field4->getFieldType());
    }

    /**
     * Тест метода isCheckable
     */
    public function testIsCheckable(): void
    {
        $radioInput = new Input(name: 'radio', type: 'radio');
        $field1 = new Field(input: $radioInput);
        $this->assertTrue($field1->isCheckable());

        $checkboxInput = new Input(name: 'checkbox', type: 'checkbox');
        $field2 = new Field(input: $checkboxInput);
        $this->assertTrue($field2->isCheckable());

        $textInput = new Input(name: 'text', type: 'text');
        $field3 = new Field(input: $textInput);
        $this->assertFalse($field3->isCheckable());

        $textarea = new Textarea(name: 'bio');
        $field4 = new Field(input: $textarea);
        $this->assertFalse($field4->isCheckable());
    }

    /**
     * Тест рендеринга Field с Input
     */
    public function testRenderFieldWithInput(): void
    {
        $input = new Input(name: 'username', id: 'username');
        $field = new Field(
            input: $input,
            label: 'Имя пользователя',
            helpText: 'Введите ваше имя',
        );

        $html = (string) $field;

        $this->assertStringContainsString('wallkit-field', $html);
        $this->assertStringContainsString('Имя пользователя', $html);
        $this->assertStringContainsString('Введите ваше имя', $html);
        $this->assertStringContainsString('name="username"', $html);
        $this->assertStringContainsString('id="username"', $html);
    }

    /**
     * Тест рендеринга Field с ошибкой
     */
    public function testRenderFieldWithError(): void
    {
        $input = new Input(name: 'email', type: 'email');
        $field = new Field(
            input: $input,
            label: 'Email',
            error: 'Некорректный адрес почты',
        );

        $html = (string) $field;

        $this->assertStringContainsString('wallkit-field--error', $html);
        $this->assertStringContainsString('Некорректный адрес почты', $html);
        $this->assertStringContainsString('role="alert"', $html);
        $this->assertStringNotContainsString('wallkit-field__help', $html);
    }

    /**
     * Тест рендеринга Field с password toggle
     */
    public function testRenderFieldWithPasswordToggle(): void
    {
        $passwordInput = new Input(name: 'password', type: 'password');
        $field = new Field(
            input: $passwordInput,
            label: 'Пароль',
        );

        $html = (string) $field;

        $this->assertStringContainsString('wallkit-field__toggle-password', $html);
        $this->assertStringContainsString('aria-label="Показать/скрыть пароль"', $html);
        $this->assertStringContainsString('👁️', $html);
        $this->assertStringContainsString('type="password"', $html);
    }

    /**
     * Тест рендеринга Field без label
     */
    public function testRenderFieldWithoutLabel(): void
    {
        $input = new Input(name: 'search');
        $field = new Field(input: $input);

        $html = (string) $field;

        $this->assertStringContainsString('wallkit-field__wrapper', $html);
        $this->assertStringNotContainsString('wallkit-field__label', $html);
        $this->assertStringContainsString('name="search"', $html);
    }

    /**
     * Тест рендеринга Field с checkbox
     */
    public function testRenderFieldWithCheckbox(): void
    {
        $checkbox = new Input(name: 'agree', value: '1', type: 'checkbox');
        $field = new Field(
            input: $checkbox,
            label: 'Согласен с условиями',
        );

        $html = (string) $field;

        $this->assertStringContainsString('wallkit-field--checkbox', $html);
        $this->assertStringContainsString('wallkit-field__checkbox-visual', $html);
        $this->assertStringContainsString('Согласен с условиями', $html);
        $this->assertStringContainsString('type="checkbox"', $html);
        $this->assertStringContainsString('value="1"', $html);
    }

    /**
     * Тест рендеринга Field с radio
     */
    public function testRenderFieldWithRadio(): void
    {
        $radio = new Input(name: 'gender', value: 'male', type: 'radio');
        $field = new Field(
            input: $radio,
            label: 'Мужской',
        );

        $html = (string) $field;

        $this->assertStringContainsString('wallkit-field--radio', $html);
        $this->assertStringContainsString('wallkit-field__radio-visual', $html);
        $this->assertStringContainsString('Мужской', $html);
        $this->assertStringContainsString('type="radio"', $html);
        $this->assertStringContainsString('value="male"', $html);
    }

    /**
     * Тест рендеринга Field с обязательным полем
     */
    public function testRenderFieldWithRequired(): void
    {
        $input = new Input(name: 'email', type: 'email', required: true);
        $field = new Field(
            input: $input,
            label: 'Email',
        );

        $html = (string) $field;

        $this->assertStringContainsString('wallkit-field__required', $html);
        $this->assertStringContainsString('*', $html);
        $this->assertStringContainsString('required', $html);
    }

    /**
     * Тест рендеринга Field с Textarea
     */
    public function testRenderFieldWithTextarea(): void
    {
        $textarea = new Textarea(name: 'bio', placeholder: 'О себе', rows: 4);
        $field = new Field(
            input: $textarea,
            label: 'Биография',
            helpText: 'Расскажите о себе',
        );

        $html = (string) $field;

        $this->assertStringContainsString('wallkit-textarea', $html);
        $this->assertStringContainsString('Биография', $html);
        $this->assertStringContainsString('Расскажите о себе', $html);
        $this->assertStringContainsString('name="bio"', $html);
        $this->assertStringContainsString('rows="4"', $html);
        $this->assertStringContainsString('placeholder="О себе"', $html);
    }

    /**
     * Тест рендеринга Field с Select
     */
    public function testRenderFieldWithSelect(): void
    {
        $options = [
            'ru' => 'Россия',
            'us' => 'США',
            'de' => 'Германия',
        ];

        $select = new Select(name: 'country', options: $options);
        $field = new Field(
            input: $select,
            label: 'Страна',
        );

        $html = (string) $field;

        $this->assertStringContainsString('wallkit-select', $html);
        $this->assertStringContainsString('Страна', $html);
        $this->assertStringContainsString('name="country"', $html);
        $this->assertStringContainsString('Россия', $html);
        $this->assertStringContainsString('США', $html);
        $this->assertStringContainsString('Германия', $html);
    }

    /**
     * Тест обработки исключений при рендеринге через renderOriginal()
     *
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRenderExceptionHandling(): void
    {
        // Создаем замоканный Input, который будет бросать исключение при рендеринге
        $mockInput = $this->createMock(Input::class);
        $mockInput
            ->method('__toString')
            ->willThrowException(new RenderException('Ошибка рендеринга поля'));

        $field = new Field(input: $mockInput);

        try {
            // Используем renderOriginal() для тестирования исключений
            $field->renderOriginal();
            $this->fail('Ожидалось исключение RenderException');
        } catch (RenderException $e) {
            $this->assertEquals(
                'Ошибка рендеринга компонента OlegV\WallKit\Form\Field\Field: Typed property OlegV\WallKit\Form\Input\Input::$disabled must not be accessed before initialization',
                $e->getMessage(),
            );
        }
    }

    /**
     * Тест, что поле корректно рендерится при преобразовании в строку
     */
    public function testFieldToStringConversion(): void
    {
        $input = new Input(name: 'test');
        $field = new Field(input: $input, label: 'Тест');

        $stringResult = (string) $field;
        $renderResult = $field->__toString();

        $this->assertIsString($stringResult);
        $this->assertIsString($renderResult);
        $this->assertEquals($stringResult, $renderResult);
        $this->assertStringContainsString('Тест', $stringResult);
    }

    /**
     * Тест работы с дополнительными классами обёртки
     */
    public function testFieldWithAdditionalClasses(): void
    {
        $input = new Input(name: 'test');
        $field = new Field(
            input: $input,
            wrapperClasses: ['mb-4', 'custom-field', 'test-class'],
        );

        $html = (string) $field;

        $this->assertStringContainsString('mb-4', $html);
        $this->assertStringContainsString('custom-field', $html);
        $this->assertStringContainsString('test-class', $html);
    }

    /**
     * Тест, что help text не показывается при наличии error
     */
    public function testHelpTextNotShownWhenErrorPresent(): void
    {
        $input = new Input(name: 'test');
        $field = new Field(
            input: $input,
            label: 'Тест',
            helpText: 'Это подсказка',
            error: 'Это ошибка',
        );

        $html = (string) $field;

        $this->assertStringContainsString('Это ошибка', $html);
        $this->assertStringContainsString('wallkit-field__error', $html);
        $this->assertStringNotContainsString('Это подсказка', $html);
        $this->assertStringNotContainsString('wallkit-field__help', $html);
    }

    /**
     * Тест консистентности работы с различными типами полей
     */
    public function testFieldTypeConsistency(): void
    {
        $types = [
            'text' => new Input(name: 'text', type: 'text'),
            'email' => new Input(name: 'email', type: 'email'),
            'password' => new Input(name: 'password', type: 'password'),
            'number' => new Input(name: 'number', type: 'number'),
            'tel' => new Input(name: 'tel', type: 'tel'),
            'url' => new Input(name: 'url', type: 'url'),
        ];

        foreach ($types as $expectedType => $input) {
            $field = new Field(input: $input);
            $this->assertEquals($expectedType, $field->getFieldType());

            // Проверяем, что рендеринг не вызывает ошибок
            $html = (string) $field;
            $this->assertStringContainsString('type="' . $expectedType . '"', $html);
        }
    }
}
