<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Form\Field;

use OlegV\WallKit\Form\Field\Field;
use OlegV\WallKit\Form\Input\Input;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class FieldTest extends TestCase
{
    // ==================== ТЕСТЫ СОЗДАНИЯ ЭКЗЕМПЛЯРА ====================

    public function testCreatesBasicField(): void
    {
        $input = new Input(name: 'username');
        $field = new Field(
            input: $input,
            label: 'Имя пользователя',
            helpText: 'Обязательное поле',
        );

        $this->assertEquals($input, $field->input);
        $this->assertEquals('Имя пользователя', $field->label);
        $this->assertEquals('Обязательное поле', $field->helpText);
        $this->assertNull($field->error);
        $this->assertTrue($field->withPasswordToggle);
    }

    public function testCreatesFieldWithError(): void
    {
        $input = new Input(name: 'email', type: 'email');
        $field = new Field(
            input: $input,
            label: 'Email',
            error: 'Введите корректный email',
        );

        $this->assertEquals('Введите корректный email', $field->error);
    }

    public function testCreatesFieldWithoutPasswordToggle(): void
    {
        $input = new Input(name: 'password', type: 'password');
        $field = new Field(
            input: $input,
            label: 'Пароль',
            withPasswordToggle: false,
        );

        $this->assertFalse($field->withPasswordToggle);
    }

    // ==================== ТЕСТЫ МЕТОДОВ КЛАССА ====================

    public function testGetWrapperClasses(): void
    {
        // Базовый класс
        $input = new Input(name: 'test');
        $field = new Field(input: $input);
        $classes = $field->getWrapperClasses();

        $this->assertContains('wallkit-field', $classes);

        // С ошибкой
        $fieldWithError = new Field(
            input: $input,
            error: 'Ошибка',
        );
        $errorClasses = $fieldWithError->getWrapperClasses();
        $this->assertContains('wallkit-field--error', $errorClasses);

        // С отключенным input
        $disabledInput = new Input(name: 'test', disabled: true);
        $fieldDisabled = new Field(input: $disabledInput);
        $disabledClasses = $fieldDisabled->getWrapperClasses();
        $this->assertContains('wallkit-field--disabled', $disabledClasses);

        // С дополнительными классами
        $fieldWithCustom = new Field(
            input: $input,
            wrapperClasses: ['custom-class', 'another-class'],
        );
        $customClasses = $fieldWithCustom->getWrapperClasses();
        $this->assertContains('custom-class', $customClasses);
        $this->assertContains('another-class', $customClasses);
    }

    public function testGetLabelId(): void
    {
        // С ID
        $inputWithId = new Input(name: 'test', id: 'test-id');
        $fieldWithId = new Field(input: $inputWithId);
        $this->assertEquals('test-id', $fieldWithId->getLabelId());

        // Без ID
        $inputWithoutId = new Input(name: 'test');
        $fieldWithoutId = new Field(input: $inputWithoutId);
        $this->assertNull($fieldWithoutId->getLabelId());
    }

    public function testShouldShowPasswordToggle(): void
    {
        // Поле пароля с toggle
        $passwordInput = new Input(name: 'password', type: 'password');
        $passwordField = new Field(
            input: $passwordInput,
            withPasswordToggle: true,
        );
        $this->assertTrue($passwordField->shouldShowPasswordToggle());

        // Поле пароля без toggle
        $passwordFieldNoToggle = new Field(
            input: $passwordInput,
            withPasswordToggle: false,
        );
        $this->assertFalse($passwordFieldNoToggle->shouldShowPasswordToggle());

        // Не поле пароля
        $textInput = new Input(name: 'text', type: 'text');
        $textField = new Field(input: $textInput);
        $this->assertFalse($textField->shouldShowPasswordToggle());
    }

    // ==================== ТЕСТЫ ПОВЕДЕНИЯ КОМПОНЕНТА ====================

    public function testFieldIsReadonlyClass(): void
    {
        $input = new Input(name: 'test');
        $field = new Field(input: $input);

        $reflection = new ReflectionClass($field);
        $this->assertTrue($reflection->isReadOnly());
    }

    public function testPropertiesAreImmutable(): void
    {
        $input = new Input(name: 'test');
        $field = new Field(
            input: $input,
            label: 'Тест',
            helpText: 'Подсказка',
            error: null,
            withPasswordToggle: true,
            wrapperClasses: [],
        );

        $this->assertInstanceOf(Input::class, $field->input);
        $this->assertEquals('Тест', $field->label);
        $this->assertEquals('Подсказка', $field->helpText);
        $this->assertNull($field->error);
        $this->assertTrue($field->withPasswordToggle);
        $this->assertIsArray($field->wrapperClasses);
        $this->assertEmpty($field->wrapperClasses);
    }
}